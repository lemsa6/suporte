<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;

class AddHashToExistingTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::whereNull('hash')->get();
        
        foreach ($tickets as $ticket) {
            $ticket->hash = Ticket::generateUniqueHash();
            $ticket->save();
        }
        
        $this->command->info("Adicionados hashes para {$tickets->count()} tickets existentes.");
    }
}


