<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $this->call(UsersSeeder::class);
        } catch (\Throwable $exception) {
            die(json_encode(['message' => $this->getStr($exception)]));
        }

    }

    /**
     * @param \Throwable|\Exception $exception
     *
     * @return string
     */
    public function getStr(\Throwable|\Exception $exception): string
    {
        return $exception->getMessage() . ' ' .
            $exception->getFile() . ' ' .
            $exception->getline();
    }
}
