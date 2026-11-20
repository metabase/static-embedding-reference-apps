<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardEmbedTest extends TestCase
{
    use RefreshDatabase;

    private Customer $acme;

    private Customer $bluebird;

    private User $ann;

    private User $ben;

    private User $cat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->acme = Customer::factory()->create(['name' => 'Test Acme']);
        $this->bluebird = Customer::factory()->create(['name' => 'Test Bluebird']);
        $this->ann = User::factory()->for($this->acme)->create();
        $this->ben = User::factory()->for($this->acme)->create();
        $this->cat = User::factory()->for($this->bluebird)->create();
    }

    /**
     * Load the dashboard page as the user and decode the token it renders.
     * Decoding verifies the signature and the expiration.
     *
     * @return array<string, mixed>
     */
    private function embedPayload(User $user, ?string $url = null): array
    {
        $response = $this->actingAs($user)->get($url ?? route('analytics'));
        $response->assertOk();

        $this->assertSame(
            1,
            preg_match('/<metabase-dashboard token="([^"]+)"/', $response->getContent(), $matches),
            'the page should render a <metabase-dashboard>',
        );

        $payload = JWT::decode($matches[1], new Key(config('services.metabase.secret_key'), 'HS256'));

        return json_decode(json_encode($payload), true);
    }

    public function test_home_page_shows_a_public_embed(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('<iframe src="'.config('services.metabase.site_url').'/public/dashboard/', false);
    }

    public function test_anonymous_visitor_is_sent_to_login(): void
    {
        $this->get(route('analytics'))->assertRedirectToRoute('login');
    }

    public function test_sign_in_with_the_right_password(): void
    {
        $this->post(route('login'), ['email' => $this->ann->email, 'password' => 'password'])
            ->assertRedirectToRoute('analytics');

        $this->assertAuthenticatedAs($this->ann);
    }

    public function test_wrong_password_does_not_sign_anyone_in(): void
    {
        $this->post(route('login'), ['email' => $this->ann->email, 'password' => 'wrong']);

        $this->assertGuest();
    }

    public function test_page_loads_embed_js_as_a_guest_embed(): void
    {
        $response = $this->actingAs($this->ann)->get(route('analytics'));

        $response->assertOk();
        $response->assertSee(config('services.metabase.site_url').'/app/embed.js', false);
        $response->assertSee('isGuest: true', false);
        $response->assertDontSee('<iframe', false);
    }

    public function test_token_locks_the_customer_id(): void
    {
        $payload = $this->embedPayload($this->ann);

        $this->assertSame(['dashboard' => config('services.metabase.dashboard_id')], $payload['resource']);
        $this->assertSame(['customer_id' => [$this->acme->id]], $payload['params']);
    }

    public function test_token_expires(): void
    {
        $payload = $this->embedPayload($this->ann);

        $this->assertGreaterThan(time(), $payload['exp']);
        $this->assertLessThanOrEqual(time() + 10 * 60, $payload['exp']);
    }

    public function test_users_of_one_customer_get_the_same_locked_value(): void
    {
        $this->assertSame(
            $this->embedPayload($this->ann)['params'],
            $this->embedPayload($this->ben)['params'],
        );
    }

    public function test_users_of_different_customers_get_different_locked_values(): void
    {
        $this->assertSame(['customer_id' => [$this->bluebird->id]], $this->embedPayload($this->cat)['params']);
        $this->assertNotSame(
            $this->embedPayload($this->ann)['params'],
            $this->embedPayload($this->cat)['params'],
        );
    }

    public function test_request_cannot_choose_the_customer(): void
    {
        $payload = $this->embedPayload($this->ann, route('analytics', ['customer_id' => $this->bluebird->id]));

        $this->assertSame(['customer_id' => [$this->acme->id]], $payload['params']);
    }
}
