<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {


        $user = User::create(['name' => 'Catherine Lapierre', 'email' => 'catherine.lapierre@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Catherine', 'last_name' => 'Lapierre', 'email' => 'catherine.lapierre@diplomatie.gouv.fr', 'phone' => NULL, 'department_id' => '1', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Carine Salmane', 'email' => 'carine.salmane@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Carine', 'last_name' => 'Salmane', 'email' => 'carine.salmane@if-liban.com', 'phone' => NULL, 'department_id' => '1', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Charbel Sawaya', 'email' => 'charbel.sawaya@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Charbel', 'last_name' => 'Sawaya', 'email' => 'charbel.sawaya@if-liban.com', 'phone' => NULL, 'department_id' => '1', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Cynthia Kanaan', 'email' => 'cynthia.kanaan@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Cynthia', 'last_name' => 'Kanaan', 'email' => 'cynthia.kanaan@if-liban.com', 'phone' => NULL, 'department_id' => '2', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Camille Brunel', 'email' => 'camille.brunel@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Camille', 'last_name' => 'Brunel', 'email' => 'camille.brunel@if-liban.com', 'phone' => NULL, 'department_id' => '3', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Dolly Bermont', 'email' => 'dolly.bermont@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Dolly', 'last_name' => 'Bermont', 'email' => 'dolly.bermont@if-liban.com', 'phone' => NULL, 'department_id' => '3', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Josette Abboud', 'email' => 'josette.abboud@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Josette', 'last_name' => 'Abboud', 'email' => 'josette.abboud@if-liban.com', 'phone' => NULL, 'department_id' => '3', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Ali Alaeddine', 'email' => 'ali.alaeddine@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Ali', 'last_name' => 'Alaeddine', 'email' => 'ali.alaeddine@if-liban.com', 'phone' => NULL, 'department_id' => '3', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Rima Mourtada', 'email' => 'rima.mourtada@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Rima', 'last_name' => 'Mourtada', 'email' => 'rima.mourtada@if-liban.com', 'phone' => NULL, 'department_id' => '3', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Mathieu Diez', 'email' => 'mathieu.diez@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Mathieu', 'last_name' => 'Diez', 'email' => 'mathieu.diez@diplomatie.gouv.fr', 'phone' => '+96176030300', 'department_id' => '4', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Denise Melki', 'email' => 'denise.melki@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Denise', 'last_name' => 'Melki', 'email' => 'denise.melki@if-liban.com', 'phone' => NULL, 'department_id' => '4', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Diana Karaki', 'email' => 'diana.dilandji@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Diana', 'last_name' => 'Karaki', 'email' => 'diana.dilandji@if-liban.com', 'phone' => NULL, 'department_id' => '4', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Nicolas Melki', 'email' => 'nicolas.melki@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Nicolas', 'last_name' => 'Melki', 'email' => 'nicolas.melki@if-liban.com', 'phone' => NULL, 'department_id' => '4', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Herminée Nurpetlian', 'email' => 'hermine.nurpetlian@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Herminée', 'last_name' => 'Nurpetlian', 'email' => 'hermine.nurpetlian@if-liban.com', 'phone' => NULL, 'department_id' => '4', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Agnès De Geoffroy', 'email' => 'agnes.de-geoffroy@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Agnès', 'last_name' => 'De Geoffroy', 'email' => 'agnes.de-geoffroy@diplomatie.gouv.fr', 'phone' => NULL, 'department_id' => '5', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Gwendoline Abou Jaoude', 'email' => 'gwendoline.aboujaoude@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Gwendoline', 'last_name' => 'Abou Jaoude', 'email' => 'gwendoline.aboujaoude@if-liban.com', 'phone' => NULL, 'department_id' => '5', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Corrine Allam', 'email' => 'corinne.allam@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Corrine', 'last_name' => 'Allam', 'email' => 'corinne.allam@if-liban.com', 'phone' => NULL, 'department_id' => '5', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Rita Hani', 'email' => 'rita.hani@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Rita', 'last_name' => 'Hani', 'email' => 'rita.hani@if-liban.com', 'phone' => NULL, 'department_id' => '5', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Camille Le Gal', 'email' => 'camille.legal@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Camille', 'last_name' => 'Le Gal', 'email' => 'camille.legal@if-liban.com', 'phone' => NULL, 'department_id' => '6', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Dania Ghaddar', 'email' => 'dania.ghaddar@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Dania', 'last_name' => 'Ghaddar', 'email' => 'dania.ghaddar@if-liban.com', 'phone' => NULL, 'department_id' => '6', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Marie Ghabril', 'email' => 'marie.ghabril@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Marie', 'last_name' => 'Ghabril', 'email' => 'marie.ghabril@if-liban.com', 'phone' => NULL, 'department_id' => '6', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Lina Harake', 'email' => 'denise.melki@example.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Lina', 'last_name' => 'Harake', 'email' => 'denise.melki@example.com', 'phone' => NULL, 'department_id' => '6', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Antoine Kanaan', 'email' => 'tony.kanaan@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Antoine', 'last_name' => 'Kanaan', 'email' => 'tony.kanaan@if-liban.com', 'phone' => NULL, 'department_id' => '6', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Jad Sawma', 'email' => 'jad.sawma@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Jad', 'last_name' => 'Sawma', 'email' => 'jad.sawma@if-liban.com', 'phone' => NULL, 'department_id' => '6', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Marielle Maroun', 'email' => 'marielle.salloum@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Marielle', 'last_name' => 'Maroun', 'email' => 'marielle.salloum@if-liban.com', 'phone' => NULL, 'department_id' => '7', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Isabelle Seigneur', 'email' => 'isabelle.seigneur@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Isabelle', 'last_name' => 'Seigneur', 'email' => 'isabelle.seigneur@diplomatie.gouv.fr', 'phone' => NULL, 'department_id' => '8', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Jinane Beydoun', 'email' => 'jinane.beydoun@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Jinane', 'last_name' => 'Beydoun', 'email' => 'jinane.beydoun@if-liban.com', 'phone' => NULL, 'department_id' => '8', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Zara Fournier', 'email' => 'zara.fournier@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Zara', 'last_name' => 'Fournier', 'email' => 'zara.fournier@if-liban.com', 'phone' => NULL, 'department_id' => '9', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Hiam Azzi', 'email' => 'hiam.azze@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Hiam', 'last_name' => 'Azzi', 'email' => 'hiam.azze@if-liban.com', 'phone' => NULL, 'department_id' => '9', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Josephine Boumrad', 'email' => 'josephine.abourjeily@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Josephine', 'last_name' => 'Boumrad', 'email' => 'josephine.abourjeily@if-liban.com', 'phone' => NULL, 'department_id' => '9', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Samer Chamseddine', 'email' => 'samer.chamseddine@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Samer', 'last_name' => 'Chamseddine', 'email' => 'samer.chamseddine@if-liban.com', 'phone' => NULL, 'department_id' => '9', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Carmen Hayek', 'email' => 'carmen.hayek@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Carmen', 'last_name' => 'Hayek', 'email' => 'carmen.hayek@if-liban.com', 'phone' => NULL, 'department_id' => '9', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Guillaume Duchemin', 'email' => 'guillaume.duchemin@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Guillaume', 'last_name' => 'Duchemin', 'email' => 'guillaume.duchemin@diplomatie.gouv.fr', 'phone' => NULL, 'department_id' => '10', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Sabine Sciortino', 'email' => 'sabine.sciortino@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Sabine', 'last_name' => 'Sciortino', 'email' => 'sabine.sciortino@diplomatie.gouv.fr', 'phone' => NULL, 'department_id' => '10', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Mélodie Bardin', 'email' => 'melodie.bardin@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Mélodie', 'last_name' => 'Bardin', 'email' => 'melodie.bardin@if-liban.com', 'phone' => NULL, 'department_id' => '11', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Christelle Fadel Pierret', 'email' => 'christelle.fadel-pierret@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Christelle', 'last_name' => 'Fadel Pierret', 'email' => 'christelle.fadel-pierret@if-liban.com', 'phone' => NULL, 'department_id' => '11', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Claudine Mrad', 'email' => 'claudine.mrad@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Claudine', 'last_name' => 'Mrad', 'email' => 'claudine.mrad@if-liban.com', 'phone' => NULL, 'department_id' => '11', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Sarah Hobeika', 'email' => 'sarah.hobeika@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Sarah', 'last_name' => 'Hobeika', 'email' => 'sarah.hobeika@if-liban.com', 'phone' => NULL, 'department_id' => '11', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Cécile Saint Martin', 'email' => 'cecile.saint-martin@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Cécile', 'last_name' => 'Saint Martin', 'email' => 'cecile.saint-martin@diplomatie.gouv.fr', 'phone' => NULL, 'department_id' => '12', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Katy Abboud', 'email' => 'ketty.abboud@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Katy', 'last_name' => 'Abboud', 'email' => 'ketty.abboud@if-liban.com', 'phone' => NULL, 'department_id' => '12', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Maha Hasoun', 'email' => 'maha.hassoun@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Maha', 'last_name' => 'Hasoun', 'email' => 'maha.hassoun@if-liban.com', 'phone' => NULL, 'department_id' => '12', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Mélanie Bouchard', 'email' => 'melanie.bouchard@diplomatie.gouv.fr', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Mélanie', 'last_name' => 'Bouchard', 'email' => 'melanie.bouchard@diplomatie.gouv.fr', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Elsa Abou Ghazale', 'email' => 'elsa.abou-ghazale@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Elsa', 'last_name' => 'Abou Ghazale', 'email' => 'elsa.abou-ghazale@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Fawzi El-Hajj', 'email' => 'fawzi.elhajj@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Fawzi', 'last_name' => 'El-Hajj', 'email' => 'fawzi.elhajj@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Sandra Khabazian', 'email' => 'sandra.khabazian@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Sandra', 'last_name' => 'Khabazian', 'email' => 'sandra.khabazian@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Léa Abi Abboud', 'email' => 'Lea.abi-abboud@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Léa', 'last_name' => 'Abi Abboud', 'email' => 'Lea.abi-abboud@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Walid Saad', 'email' => 'walid.saad@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Walid', 'last_name' => 'Saad', 'email' => 'walid.saad@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Christiane Safi', 'email' => 'christiane.safi@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Christiane', 'last_name' => 'Safi', 'email' => 'christiane.safi@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Liliane Safi', 'email' => 'liliane.safi@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Liliane', 'last_name' => 'Safi', 'email' => 'liliane.safi@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Hassane Toubia', 'email' => 'hassan.toubia@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Hassane', 'last_name' => 'Toubia', 'email' => 'hassan.toubia@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Antonios Younes', 'email' => 'tony.younes@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Antonios', 'last_name' => 'Younes', 'email' => 'tony.younes@if-liban.com', 'phone' => NULL, 'department_id' => '13', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Sophie Jarjat', 'email' => 'sophie.jarjat@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Sophie', 'last_name' => 'Jarjat', 'email' => 'sophie.jarjat@if-liban.com', 'phone' => NULL, 'department_id' => '14', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Aida Ajami', 'email' => 'aida.ezzedine@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Aida', 'last_name' => 'Ajami', 'email' => 'aida.ezzedine@if-liban.com', 'phone' => NULL, 'department_id' => '14', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Hanane Jabbour', 'email' => 'hanane.jabbour@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Hanane', 'last_name' => 'Jabbour', 'email' => 'hanane.jabbour@if-liban.com', 'phone' => NULL, 'department_id' => '14', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Mona Sabbah', 'email' => 'mona.sabbah@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Mona', 'last_name' => 'Sabbah', 'email' => 'mona.sabbah@if-liban.com', 'phone' => NULL, 'department_id' => '14', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Youssef Takach', 'email' => 'youssef.takach@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Youssef', 'last_name' => 'Takach', 'email' => 'youssef.takach@if-liban.com', 'phone' => NULL, 'department_id' => '14', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Emmanuel Khoury', 'email' => 'emmanuel.khoury@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Emmanuel', 'last_name' => 'Khoury', 'email' => 'emmanuel.khoury@if-liban.com', 'phone' => NULL, 'department_id' => '15', 'profile_image' => NULL, 'is_supervisor' => '1', 'recieve_email' => '1', 'allow_order' => '0', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Georges Mehrez', 'email' => 'georges.mehrez@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Georges', 'last_name' => 'Mehrez', 'email' => 'georges.mehrez@if-liban.com', 'phone' => NULL, 'department_id' => '15', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Maribel Moussi', 'email' => 'maribel.moussi@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Maribel', 'last_name' => 'Moussi', 'email' => 'maribel.moussi@if-liban.com', 'phone' => NULL, 'department_id' => '15', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Nicolas Mansour', 'email' => 'nicolas.mansour@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Nicolas', 'last_name' => 'Mansour', 'email' => 'nicolas.mansour@if-liban.com', 'phone' => NULL, 'department_id' => '15', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);

        $user = User::create(['name' => 'Nada Dennaoui', 'email' => 'nada.dennaoui@if-liban.com', 'password' => Hash::make('password')]);
        Employee::create(['first_name' => 'Nada', 'last_name' => 'Dennaoui', 'email' => 'nada.dennaoui@if-liban.com', 'phone' => NULL, 'department_id' => '15', 'profile_image' => NULL, 'is_supervisor' => '0', 'recieve_email' => '1', 'allow_order' => '1', 'user_id' => $user->id, 'roles' => []]);



        // Create HR user
        $userHr = User::create([
            'name' => 'HR Manager',
            'email' => 'hr@company.com',
            'password' => Hash::make('password'), // Default password for testing
        ]);

        Employee::create([
            'first_name' => 'HR',
            'last_name' => 'Manager',
            'email' => 'hr@company.com',
            'phone' => '123456789',
            'department_id' => 18, // HR Department
            'user_id' => $userHr->id,
            'roles' => ['hr'],
            'position' => 'HR Manager',
            'administrativ_residence' => 'Beyrouth',
            'service' => 'any service',
        ]);

        // Create Supervisor user
        $userSupervisor = User::create([
            'name' => 'Supervisor One',
            'email' => 'supervisor@company.com',
            'password' => Hash::make('password'),
        ]);

        Employee::create([
            'first_name' => 'Supervisor',
            'last_name' => 'Manager',
            'email' => 'supervisor@company.com',
            'phone' => '987654321',
            'department_id' => 16, // IT Department
            'user_id' => $userSupervisor->id,
            'roles' => ['supervisor'],
            'position' => 'IT Supervisor',
            'administrativ_residence' => 'Beyrouth',
            'service' => 'any service',
        ]);

        // Create Employee user
        $userEmployee = User::create([
            'name' => 'Employee One',
            'email' => 'employee@company.com',
            'password' => Hash::make('password'),
        ]);

        Employee::create([
            'first_name' => 'Employee',
            'last_name' => 'Manager',
            'email' => 'employee@company.com',
            'phone' => '555123456',
            'department_id' => 16, // IT Department
            'user_id' => $userEmployee->id,
            'roles' => ['employee'],
            'position' => 'Tschnical Support',
            'administrativ_residence' => 'Beyrouth',
            'service' => 'any service',
        ]);

        // Create SG user
        $userSga = User::create([
            'name' => 'SG Manager',
            'email' => 'sg@company.com',
            'password' => Hash::make('password'),
        ]);

        Employee::create([
            'first_name' => 'SG',
            'last_name' => 'Manager',
            'email' => 'sg@company.com',
            'phone' => '444123456',
            'department_id' => 17, // Finance Department
            'user_id' => $userSga->id,
            'roles' => ['sg'],
            'position' => 'SG',
            'administrativ_residence' => 'Beyrouth',
            'service' => 'any service',
        ]);
    }
}
