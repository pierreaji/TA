<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Distributor;
use App\Models\Item;
use App\Models\ItemAssign;
use App\Models\ItemIn;
use App\Models\ItemRequest;
use App\Models\User;
use App\Models\UserSales;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Admin SyncMas';
        $user->email = 'admin@sales.com';
        $user->password = Hash::make('password');
        $user->role = 'Admin';
        $user->save();

        $user = new User();
        $user->name = 'Warehouse SyncMas';
        $user->email = 'warehouse@sales.com';
        $user->password = Hash::make('password');
        $user->role = 'Warehouse';
        $user->save();

        $user = new User();
        $user->name = 'Ujang Diki';
        $user->email = 'sales@sales.com';
        $user->password = Hash::make('password');
        $user->role = 'Sales';
        $user->save();

        $sales = new UserSales();
        $sales->id_user = $user->id;
        $sales->type = 'Motorcycle';
        $sales->skck = 'dummy';
        $sales->ktp = 'dummy';
        $sales->sim = 'dummy';
        $sales->stnk = 'dummy';
        $sales->nik = '321307899627111'; 
        $sales->number = '085314000141'; 
        $sales->address = 'Subang, Jawa Barat'; 
        $sales->pas_foto = 'dummy';
        $sales->sertifikat = 'dummy';
        $sales->agreement = 'dummy';
        $sales->must_change_password = false;
        $sales->save();

        $user = new User();
        $user->name = 'Rozak Nurhakim';
        $user->email = 'sales2@sales.com';
        $user->password = Hash::make('password');
        $user->role = 'Sales';
        $user->save();

        $sales = new UserSales();
        $sales->id_user = $user->id;
        $sales->type = 'Car';
        $sales->skck = 'dummy';
        $sales->ktp = 'dummy';
        $sales->nik = '321307899627111'; 
        $sales->number = '085314000141'; 
        $sales->address = 'Subang, Jawa Barat'; 
        $sales->sim = 'dummy';
        $sales->stnk = 'dummy';
        $sales->pas_foto = 'dummy';
        $sales->sertifikat = 'dummy';
        $sales->agreement = 'dummy';
        $sales->must_change_password = false;
        $sales->save();

        $category = new Category();
        $category->name = 'Food';
        $category->save();

        $category = new Category();
        $category->name = 'Drink';
        $category->save();

        $distributor = new Distributor();
        $distributor->name = 'PT So Good Food';
        $distributor->phone = '081234567890';
        $distributor->address = 'Boyolali';
        $distributor->save();

        $distributor = new Distributor();
        $distributor->name = 'PT Indofood Sukses Makmur Tbk';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jakarta';
        $distributor->save();

        $distributor = new Distributor();
        $distributor->name = 'PT GarudaFood Putra Putri Jaya';
        $distributor->phone = '081234567890';
        $distributor->address = 'Gresik';
        $distributor->save();

        $distributor = new Distributor();
        $distributor->name = 'PT. Kapal Api Global';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jakarta';
        $distributor->save();

        $distributor = new Distributor();
        $distributor->name = 'PT Mondelēz Indonesia';
        $distributor->phone = '081234567890';
        $distributor->address = 'Bekasi';
        $distributor->save();

        $distributor = new Distributor();
        $distributor->name = 'PT. Mulya Jaya';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jl. Industri No.11, RT.01, Arjuna, Kec. Cicendo, Kota Bandung, Jawa Barat 40172';
        $distributor->save();

        $distributor = new Distributor();
        $distributor->name = 'PT. ABC President Indonesia';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jl. Gading Utama Tim. I No.7';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'PT. Arta Boga Cemerlang';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jl. Soekarno Hatta No.709, Sukapura, Kec. Kiaracondong, Kota Bandung, Jawa Barat 40255';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'PD. UJ';
        $distributor->phone = '081234567890';
        $distributor->address = 'Kota Bandung, Jawa Barat 40255';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'PT. Arta Boga Cemerlang';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jl.Tomang Raya No. 21-23 Jakarta ';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'PT. Cipta Niaga Semesta';
        $distributor->phone = '081234567890';
        $distributor->address = 'JL. Daan Mogot, Km. 18, Jakarta.';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'PT. Mayora Indah Tbk';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jl.Tomang Raya No. 21-23 Jakarta';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'PT. Sinar Mentari';
        $distributor->phone = '081234567890';
        $distributor->address = 'Bandung';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'PT. Putri Daya Usahatama';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jl. Rumah Sakit No.133, Mekar Mulya, Kec. Panyileukan, Kota Bandung, Jawa Barat 40619';
        $distributor->save(); 

        $distributor = new Distributor();
        $distributor->name = 'CV. Adhikari Primario';
        $distributor->phone = '081234567890';
        $distributor->address = 'Jl. Raya Purwadadi No.109, Purwadadi Bar., Kec. Purwadadi, Kabupaten Subang, Jawa Barat 41261';
        $distributor->save(); 



        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '1';
        $item->name = 'Real Good Chocolate';
        $item->type = 'Carton';
        $item->sale_price = str_replace('.', '', '25000');
        $item->distributor_price = str_replace('.', '', '24000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '1';
        $item->name = 'Sosis So Nice 1000';
        $item->type = 'Pless';
        $item->sale_price = str_replace('.', '', '17000');
        $item->distributor_price = str_replace('.', '', '15000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '1';
        $item->name = 'Sosis So Nice 2000';
        $item->type = 'Pless';
        $item->sale_price = str_replace('.', '', '18500');
        $item->distributor_price = str_replace('.', '', '17000');
        $item->save();


        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '1';
        $item->name = 'Sosis Jumbo Temen Mie';
        $item->type = 'Pless';
        $item->sale_price = str_replace('.', '', '19500');
        $item->distributor_price = str_replace('.', '', '18000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '5';
        $item->name = 'Oreo Vanilla Cream';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '24000');
        $item->distributor_price = str_replace('.', '', '23000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '5';
        $item->name = 'Oreo Ice Cream Blue';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '24000');
        $item->distributor_price = str_replace('.', '', '23000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '5';
        $item->name = 'Oreo Chocolate Sandwitch';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '18000');
        $item->distributor_price = str_replace('.', '', '17000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '3';
        $item->name = 'Garuda Kacang Kulit';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '18000');
        $item->distributor_price = str_replace('.', '', '17000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '3';
        $item->name = 'Garuda Kacang Atom';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '18000');
        $item->distributor_price = str_replace('.', '', '17000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '3';
        $item->name = 'Gery Salute Marie Susu';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '18000');
        $item->distributor_price = str_replace('.', '', '17000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '3';
        $item->name = 'Chocolatos Coklat';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '21000');
        $item->distributor_price = str_replace('.', '', '18000');
        $item->save();

        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '3';
        $item->name = 'Chocolatos Vanilla';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '21000');
        $item->distributor_price = str_replace('.', '', '18000');
        $item->save();

        
        $item = new Item();
        $item->id_category = $category->id;
        $item->id_distributor = '3';
        $item->name = 'Gery Salute Malkist';
        $item->type = 'Pack';
        $item->sale_price = str_replace('.', '', '7000');
        $item->distributor_price = str_replace('.', '', '6500');
        $item->save();


        $in = new ItemIn();
        $in->id_item = '1';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '2';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '3';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '4';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();
        
        $in = new ItemIn();
        $in->id_item = '5';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '6';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '7';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '8';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '9';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '10';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();

        $in = new ItemIn();
        $in->id_item = '11';
        $in->id_user = '1';
        $in->stock = 10;
        $in->incoming_item_date = Carbon::now();
        $in->save();
    }
}
