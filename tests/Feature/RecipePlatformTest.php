<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\MealPlan;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\ShoppingItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipePlatformTest extends TestCase
{
    use RefreshDatabase;

    private function recipe(array $overrides = []): Recipe
    {
        static $externalId = 500;

        return Recipe::factory()->create(array_merge([
            'external_id' => $externalId++,
            'name' => 'Nasi Goreng Spesial',
            'ingredients' => ['Beras', 'Kecap manis'],
            'instructions' => ['Tumis beras.', 'Siram kecap.'],
        ], $overrides));
    }

    public function test_home_page_renders_with_catalogue(): void
    {
        $this->recipe(['rating' => 4.8, 'review_count' => 120]);

        $this->get(route('beranda'))
            ->assertOk()
            ->assertSee('Nasi Goreng Spesial')
            ->assertSee('Jelajah');
    }

    public function test_explore_page_filters_by_cuisine_and_difficulty(): void
    {
        $this->recipe(['cuisine' => 'Japanese', 'difficulty' => 'Easy']);
        $this->recipe(['cuisine' => 'Italian', 'difficulty' => 'Hard']);

        $this->get(route('jelajah', ['cuisine' => 'Japanese']))
            ->assertOk()
            ->assertSee('Japanese')
            ->assertDontSee('Italian');

        $this->get(route('jelajah', ['difficulty' => 'Hard']))
            ->assertOk()
            ->assertSee('Italian')
            ->assertDontSee('Japanese');
    }

    public function test_explore_cards_partial_returns_only_the_grid(): void
    {
        $this->recipe();

        $this->get(route('jelajah.kartu'))
            ->assertOk()
            ->assertSee('Nasi Goreng Spesial');
    }

    public function test_recipe_show_displays_ingredients_and_steps(): void
    {
        $recipe = $this->recipe();

        $this->get(route('recipes.show', $recipe))
            ->assertOk()
            ->assertSee('Beras')
            ->assertSee('Tumis beras.')
            ->assertSee('Mode masak');
    }

    public function test_cook_mode_page_renders(): void
    {
        $recipe = $this->recipe();

        $this->get(route('recipes.cook', $recipe))
            ->assertOk()
            ->assertSee('Mode masak')
            ->assertSee('Tumis beras.');
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/resep/tidak-ada')->assertNotFound();
    }

    public function test_category_and_search_pages_render(): void
    {
        $this->recipe(['cuisine' => 'Thai', 'tags' => ['Street food']]);

        $this->get(route('kategori'))->assertOk()->assertSee('Thai');
        $this->get(route('cari', ['q' => 'Goreng']))->assertOk()->assertSee('Nasi Goreng Spesial');
        $this->get(route('statistik'))->assertOk()->assertSee('Statistik dapur');
    }

    public function test_guest_is_redirected_when_favouriting(): void
    {
        $recipe = $this->recipe();

        $this->post(route('favorites.store', $recipe))->assertRedirect(route('login'));
    }

    public function test_user_can_toggle_favorite(): void
    {
        $user = User::factory()->create();
        $recipe = $this->recipe();

        $this->actingAs($user)->post(route('favorites.store', $recipe))->assertRedirect();

        $this->assertDatabaseHas('favorites', ['user_id' => $user->id, 'recipe_id' => $recipe->id]);

        $this->actingAs($user)
            ->postJson(route('favorites.store', $recipe))
            ->assertOk()
            ->assertJson(['favorited' => false]);

        $this->assertDatabaseMissing('favorites', ['user_id' => $user->id, 'recipe_id' => $recipe->id]);
    }

    public function test_user_can_submit_a_review(): void
    {
        $user = User::factory()->create();
        $recipe = $this->recipe();

        $this->actingAs($user)
            ->from(route('recipes.show', $recipe))
            ->post(route('reviews.store', $recipe), [
                'rating' => 5,
                'body' => 'Enak banget, wajib dicoba lagi.',
            ])
            ->assertRedirect(route('recipes.show', $recipe))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('reviews', [
            'recipe_id' => $recipe->id,
            'user_id' => $user->id,
            'rating' => 5,
        ]);
    }

    public function test_review_validation_rejects_short_body(): void
    {
        $user = User::factory()->create();
        $recipe = $this->recipe();

        $this->actingAs($user)
            ->from(route('recipes.show', $recipe))
            ->post(route('reviews.store', $recipe), ['rating' => 5, 'body' => 'pendek'])
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_guest_review_uses_supplied_name(): void
    {
        $recipe = $this->recipe();

        $this->from(route('recipes.show', $recipe))
            ->post(route('reviews.store', $recipe), [
                'rating' => 4,
                'body' => 'Lumayan enak untuk pemula seperti saya.',
                'author_name' => 'Tamu Dapur',
            ])
            ->assertRedirect(route('recipes.show', $recipe));

        $this->assertDatabaseHas('reviews', ['author_name' => 'Tamu Dapur', 'user_id' => null]);
    }

    public function test_user_cannot_delete_someone_elses_review(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $recipe = $this->recipe();

        $review = Review::create([
            'recipe_id' => $recipe->id,
            'user_id' => $owner->id,
            'author_name' => $owner->name,
            'rating' => 5,
            'body' => 'Enak banget wilayah saya.',
        ]);

        $this->actingAs($other)->delete(route('reviews.destroy', $review))->assertForbidden();

        $this->assertDatabaseHas('reviews', ['id' => $review->id]);
    }

    public function test_collections_can_be_created_and_filled(): void
    {
        $user = User::factory()->create();
        $recipe = $this->recipe();

        $this->actingAs($user)
            ->post(route('koleksi.store'), [
                'name' => 'Menu Cepat',
                'accent' => 'sky',
                'description' => 'Resep di bawah 30 menit.',
            ])
            ->assertRedirect(route('koleksi.index'));

        $collection = Collection::firstWhere('user_id', $user->id);

        $this->actingAs($user)
            ->postJson(route('koleksi.resep', $collection), ['recipe_id' => $recipe->id])
            ->assertOk()
            ->assertJson(['in_collection' => true, 'count' => 1]);

        $this->assertDatabaseHas('collection_recipe', [
            'collection_id' => $collection->id,
            'recipe_id' => $recipe->id,
        ]);
    }

    public function test_user_cannot_open_another_users_collection(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $collection = Collection::create([
            'user_id' => $owner->id,
            'name' => 'Milik orang lain',
        ]);

        $this->actingAs($other)->get(route('koleksi.show', $collection))->assertNotFound();
    }

    public function test_meal_plan_can_be_scheduled_and_moved(): void
    {
        $user = User::factory()->create();
        $recipe = $this->recipe(['total_minutes' => 20]);

        $this->actingAs($user)
            ->post(route('rencana.store'), [
                'recipe_id' => $recipe->id,
                'planned_for' => now()->toDateString(),
                'slot' => MealPlan::SLOTS[0],
                'servings' => 3,
            ])
            ->assertRedirect();

        $plan = MealPlan::firstWhere('user_id', $user->id);

        $this->assertNotNull($plan);
        $this->assertSame(3, $plan->servings);

        $this->actingAs($user)
            ->put(route('rencana.update', $plan), [
                'planned_for' => now()->toDateString(),
                'slot' => MealPlan::SLOTS[2],
                'servings' => 6,
            ])
            ->assertRedirect();

        $this->assertSame(6, $plan->fresh()->servings);
    }

    public function test_meal_plan_uses_slot_and_date_uniqueness(): void
    {
        $user = User::factory()->create();
        $recipe = $this->recipe();

        $payload = [
            'recipe_id' => $recipe->id,
            'planned_for' => now()->toDateString(),
            'slot' => MealPlan::SLOTS[1],
            'servings' => 2,
        ];

        $this->actingAs($user)->post(route('rencana.store'), $payload)->assertRedirect();
        $this->actingAs($user)->post(route('rencana.store'), $payload)->assertRedirect();

        $this->assertSame(1, MealPlan::where('user_id', $user->id)->count());
    }

    public function test_shopping_items_can_be_added_and_toggled(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('belanja.store'), ['name' => 'Bawang merah 1 kg', 'category' => 'Sayur', 'quantity' => 2])
            ->assertRedirect();

        $item = ShoppingItem::firstWhere('user_id', $user->id);

        $this->actingAs($user)
            ->postJson(route('belanja.centang', $item))
            ->assertOk()
            ->assertJson(['is_checked' => true, 'done' => 1]);

        $this->assertTrue($item->fresh()->is_checked);
    }

    public function test_importing_ingredients_from_a_recipe_adds_new_items_only(): void
    {
        $user = User::factory()->create();
        $recipe = $this->recipe(['ingredients' => ['Beras', 'Kecap manis']]);

        ShoppingItem::create([
            'user_id' => $user->id,
            'name' => 'Beras',
            'category' => 'Sembako',
        ]);

        $this->actingAs($user)
            ->post(route('belanja.impor'), ['recipe_ids' => [$recipe->id]])
            ->assertRedirect()
            ->assertSessionHas('status');

        $names = ShoppingItem::where('user_id', $user->id)->pluck('name');

        $this->assertCount(2, $names);
        $this->assertTrue($names->contains('Kecap manis'));
    }

    public function test_json_api_returns_recipes_with_meta(): void
    {
        $this->recipe(['name' => 'Ayam Geprek API']);

        $this->getJson(route('api.resep.index'))
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Ayam Geprek API');

        $this->getJson(route('api.resep.show', Recipe::first()->slug))
            ->assertOk()
            ->assertJsonPath('data.name', 'Ayam Geprek API');
    }

    public function test_registration_logs_the_user_in(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Dita Ayu',
            'email' => 'dita@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('beranda'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'dita@example.com']);
    }

    public function test_login_rejects_wrong_password(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->from(route('login'))
            ->post(route('login.store'), ['email' => $user->email, 'password' => 'salah'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
