<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StripeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stripe_configurations')->insert([
            [
                'name' => 'Stripe Test Keys',
                'stripe_key' => 'pk_test_51JHji9CyH4JUlqwqQKz9fd5lnr0UEb8Wzc8sSW7kuPdKmRtP3zVBym1f3Ps3YOHIMgzM4CXs2sT6sCMz5OAqf93c000dEtDJB3',
                'stripe_secret' => 'sk_test_51JHji9CyH4JUlqwqDESDmUbFsnDvSkLFF23HJjwhKXSwBuIJPwXfuQvQyw1SyT3DuwcZtR69n9HTBa9zfypOgpIt00MUTOj66A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
