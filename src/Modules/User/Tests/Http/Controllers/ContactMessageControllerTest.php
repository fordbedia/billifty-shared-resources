<?php

namespace App\Http\Controllers {
    if (! class_exists(Controller::class)) {
        class Controller {}
    }
}

namespace BilliftySDK\SharedResources\Modules\User\Tests\Http\Controllers {

    use BilliftySDK\SharedResources\Modules\User\Http\Controllers\ContactMessageController;
    use BilliftySDK\SharedResources\Modules\User\Mail\ContactMessageMail;
    use BilliftySDK\SharedResources\Modules\User\Models\User;
    use BilliftySDK\SharedResources\TestCase\BaseTest;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Schema;

    class ContactMessageControllerTest extends BaseTest
    {
        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
        }

        protected function setUp(): void
        {
            parent::setUp();

            config()->set('auth.guards.api', [
                'driver' => 'session',
                'provider' => 'users',
            ]);
            config()->set('auth.providers.users', [
                'driver' => 'eloquent',
                'model' => User::class,
            ]);
            config()->set('mail.admin.address', 'admin@billifty.test');

            $this->ensureContactMessagesTableExists();

            Route::post('/test/contact-message', [ContactMessageController::class, 'store']);
        }

        /** @test */
        public function guest_contact_message_requires_and_stores_name_and_email(): void
        {
            Mail::fake();

            $response = $this->postJson('/test/contact-message', [
                'name' => 'Guest Sender',
                'email' => 'guest@example.com',
                'subject' => 'Billing question',
                'message' => 'Can you help with my invoice?',
            ]);

            $response->assertCreated();
            $this->assertDatabaseHas('contact_messages', [
                'user_id' => null,
                'name' => 'Guest Sender',
                'email' => 'guest@example.com',
                'subject' => 'Billing question',
                'message' => 'Can you help with my invoice?',
            ]);

            Mail::assertSent(ContactMessageMail::class, 2);
            Mail::assertSent(ContactMessageMail::class, fn (ContactMessageMail $mail) => $mail->hasTo('guest@example.com'));
            Mail::assertSent(ContactMessageMail::class, fn (ContactMessageMail $mail) => $mail->hasTo('admin@billifty.test'));
        }

        /** @test */
        public function authenticated_contact_message_stores_the_authenticated_user_id(): void
        {
            Mail::fake();
            $user = new User([
                'name' => 'Authenticated Sender',
                'email' => 'authenticated@example.com',
            ]);
            $user->id = 123;
            $user->exists = true;

            $response = $this->actingAs($user, 'api')->postJson('/test/contact-message', [
                'subject' => 'Feature request',
                'message' => 'Please add recurring reminders.',
            ]);

            $response->assertCreated();
            $this->assertDatabaseHas('contact_messages', [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'subject' => 'Feature request',
                'message' => 'Please add recurring reminders.',
            ]);

            Mail::assertSent(ContactMessageMail::class, 2);
            Mail::assertSent(ContactMessageMail::class, fn (ContactMessageMail $mail) => $mail->hasTo($user->email));
            Mail::assertSent(ContactMessageMail::class, fn (ContactMessageMail $mail) => $mail->hasTo('admin@billifty.test'));
        }

        /** @test */
        public function guest_contact_message_rejects_missing_name_and_email(): void
        {
            Mail::fake();

            $response = $this->postJson('/test/contact-message', [
                'subject' => 'Support request',
                'message' => 'I need help.',
            ]);

            $response->assertUnprocessable();
            $response->assertJsonValidationErrors(['name', 'email']);
            Mail::assertNothingSent();
        }

        private function ensureContactMessagesTableExists(): void
        {
            if (! Schema::hasTable('contact_messages')) {
                Schema::create('contact_messages', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->string('name')->nullable();
                    $table->string('email')->nullable();
                    $table->string('subject');
                    $table->text('message');
                    $table->timestamps();
                });

                return;
            }

            DB::table('contact_messages')->truncate();
        }
    }
}
