<?php

namespace Database\Seeders;

use App\Models\Cashout;
use App\Models\CashRegisterSession;
use App\Models\Expense;
use App\Models\Income;
use App\Models\InventoryIngredient;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\RestaurantException;
use App\Models\RestaurantTable;
use App\Models\StaffAttendance;
use App\Models\StaffSchedule;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ClientDemoSeeder extends Seeder
{
    private User $employee;
    private array $menu = [];
    private array $tables = [];
    private array $sessions = [];

    public function run(): void
    {
        $this->employee = User::query()->whereIn('role', ['owner','admin','superadmin'])->first() ?? User::query()->first()
            ?? throw new RuntimeException('Create an employee account before running the client demo seeder.');

        DB::transaction(function () {
            $this->seedMenu();
            $this->seedTables();
            $this->seedReservations();
            $this->seedSuppliersAndInventory();
            $this->seedRegisterSessions();
            $this->seedOrders();
            $this->seedFinance();
            $this->seedStaffActivity();
            $this->seedRestaurantExceptions();
        });
    }

    private function seedMenu(): void
    {
        $rows = [
            ['Demo Truffle Croquettes','Starters',8.50,32,8,'kitchen','Gluten, milk'],
            ['Demo Burrata Garden','Starters',11.90,18,5,'kitchen','Milk'],
            ['Demo House Smash','Smash Burger',14.50,24,6,'kitchen','Gluten, egg, milk'],
            ['Demo Mediterranean Bowl','Bol de Arroz',13.20,20,5,'kitchen','Sesame'],
            ['Demo Basque Cheesecake','Postres de la Casa',6.80,9,4,'desserts','Milk, egg'],
            ['Demo Citrus Spritz','Cocteles',8.00,28,7,'bar',null],
            ['Demo Fresh Lemonade','Bebidas',3.50,40,10,'drinks',null],
            ['Demo Specialty Coffee','Cafes',2.80,45,10,'bar','Milk'],
        ];
        foreach ($rows as $index => [$name,$category,$price,$stock,$low,$station,$allergens]) {
            $categoryModel = MenuCategory::firstOrCreate(['name'=>$category], ['sort_order'=>$index,'is_active'=>true]);
            $item = MenuItem::updateOrCreate(['name'=>$name], [
                'category'=>$category,'menu_category_id'=>$categoryModel->id,'description'=>'Client demo menu item prepared with seasonal ingredients.',
                'ingredients'=>'Fresh restaurant ingredients','allergens'=>$allergens,'price'=>$price,'stock_quantity'=>$stock,
                'low_stock_threshold'=>$low,'availability_status'=>'available','item_kind'=>$index===3?'lunch_menu':'regular','station'=>$station,'is_active'=>true,'sort_order'=>90+$index,
            ]);
            $item->options()->updateOrCreate(['type'=>'extra','name'=>'Demo extra portion'], ['price_adjustment'=>2.00,'is_active'=>true,'sort_order'=>1]);
            $this->menu[]=$item;
        }
    }

    private function seedTables(): void
    {
        $areas=['Main Room','Main Room','Window','Window','Terrace','Terrace','Private Room','Bar'];
        foreach (range(41,48) as $i=>$number) {
            $this->tables[] = RestaurantTable::updateOrCreate(['number'=>$number], ['seats'=>[2,4,4,6,2,4,8,2][$i],'area'=>$areas[$i],'service_status'=>'available','assigned_user_id'=>$this->employee->id]);
        }
    }

    private function seedReservations(): void
    {
        $names=['Sofia Martinez','Daniel Costa','Emma Wilson','Hugo Laurent','Lina Rossi','Noah Garcia','Mia Jensen','Leo Silva'];
        $statuses=['confirmed','pending','arrived','seated','confirmed','cancelled','no_show','confirmed'];
        foreach ($names as $i=>$name) {
            Reservation::updateOrCreate(['customer_email'=>'client.demo'.($i+1).'@example.com'], [
                'customer_name'=>$name,'customer_phone'=>'+34 600 100 '.str_pad((string)$i,3,'0',STR_PAD_LEFT),'party_size'=>[2,4,3,6,2,5,4,8][$i],
                'reservation_time'=>now()->addDays($i<5?0:$i-4)->setTime($i%2?20:13,15+($i*5)%40),'notes'=>'Client demo reservation '.($i+1),
                'allergies'=>$i===2?'Peanuts':($i===5?'Gluten':null),'restaurant_table_id'=>$this->tables[$i]->id,'status'=>$statuses[$i],'user_id'=>$this->employee->id,
            ]);
        }
    }

    private function seedSuppliersAndInventory(): void
    {
        $supplierRows=[
            ['Demo Fresh Market','Laura Vega','+34 910 001 101','fresh@demo.test'],['Demo Artisan Bakery','Pablo Ruiz','+34 910 001 102','bakery@demo.test'],
            ['Demo Beverage Co.','Sara King','+34 910 001 103','drinks@demo.test'],['Demo Local Dairy','Marco Alba','+34 910 001 104','dairy@demo.test'],
            ['Demo Sustainable Meats','Ana Sol','+34 910 001 105','meat@demo.test'],
        ];
        $suppliers=[];
        foreach($supplierRows as [$name,$contact,$phone,$email]) $suppliers[]=Supplier::updateOrCreate(['name'=>$name],['contact_name'=>$contact,'phone'=>$phone,'email'=>$email,'notes'=>'Client demo supplier']);
        $ingredientRows=[
            ['Demo Beef patties','unit',45,12,1.60,4],['Demo Brioche buns','unit',38,10,.65,1],['Demo Potatoes','kg',18,6,1.20,0],['Demo Burrata','unit',14,5,2.10,3],
            ['Demo Rice','kg',12,4,1.70,0],['Demo Cheesecake mix','portion',9,4,1.30,3],['Demo Citrus','kg',7,3,2.40,0],['Demo Coffee beans','kg',4.5,2,14.00,2],
        ];
        $ingredients=[];
        foreach($ingredientRows as $i=>[$name,$unit,$stock,$low,$cost,$supplier]) {
            $ingredient=InventoryIngredient::updateOrCreate(['name'=>$name],['unit'=>$unit,'stock_quantity'=>$stock,'low_stock_threshold'=>$low,'cost_per_unit'=>$cost,'supplier_id'=>$suppliers[$supplier]->id,'is_active'=>true]);
            $ingredients[]=$ingredient;
            StockMovement::updateOrCreate(['reference'=>'CLIENT-DEMO-STOCK-'.($i+1)],['target_type'=>'ingredient','target_id'=>$ingredient->id,'movement_type'=>$i===6?'waste':'purchase','quantity'=>$i===6?-1:($i+5),'unit_cost'=>$cost,'supplier_id'=>$suppliers[$supplier]->id,'user_id'=>$this->employee->id,'notes'=>'Client demo stock movement','occurred_at'=>now()->subHours($i+1)]);
        }
        $recipeMap=[[2,0,1],[2,1,1],[0,2,.15],[1,3,1],[3,4,.18],[4,5,1],[5,6,.12],[7,7,.018]];
        foreach($recipeMap as [$menu,$ingredient,$quantity]) $this->menu[$menu]->recipeIngredients()->syncWithoutDetaching([$ingredients[$ingredient]->id=>['quantity'=>$quantity]]);
    }

    private function seedRegisterSessions(): void
    {
        CashRegisterSession::where('notes','like','Client demo register%')->delete();
        foreach(range(4,0) as $i) {
            $opening=150+$i*10; $expected=$opening+320+$i*35; $actual=$expected+($i%3-1);
            $this->sessions[]=CashRegisterSession::create(['opened_by'=>$this->employee->id,'closed_by'=>$this->employee->id,'opening_float'=>$opening,'expected_cash'=>$expected,'actual_cash'=>$actual,'difference'=>$actual-$expected,'opened_at'=>now()->subDays($i)->setTime(10,0),'closed_at'=>now()->subDays($i)->setTime(23,0),'status'=>'closed','notes'=>'Client demo register '.$i]);
        }
        $this->sessions[]=CashRegisterSession::create(['opened_by'=>$this->employee->id,'opening_float'=>180,'opened_at'=>now()->setTime(10,0),'status'=>'open','notes'=>'Client demo register open']);
    }

    private function seedOrders(): void
    {
        Order::where('notes','like','Client demo order%')->delete();
        $definitions=[
            ['completed','dine_in',0,[[2,2],[6,2]],0,0],['completed','delivery',1,[[3,1],[5,2]],2,1],['completed','takeaway',2,[[0,2],[7,2]],0,0],
            ['completed','pickup',3,[[1,1],[4,2]],3,0],['completed','dine_in',4,[[2,3],[4,3]],0,2],
            ['new','dine_in',0,[[2,1],[6,2]],0,0],['preparing','dine_in',0,[[3,2],[5,2]],0,0],['ready','delivery',0,[[0,1],[7,1]],0,0],
            ['cancelled','pickup',1,[[1,2]],0,0],
        ];
        $methods=['cash','card','bizum','online','cash'];
        foreach($definitions as $i=>[$workflow,$type,$days,$lines,$discount,$service]) {
            $isDone=in_array($workflow,['completed','cancelled'],true); $table=$type==='dine_in'?$this->tables[$i%count($this->tables)]:null;
            $order=Order::create(['restaurant_table_id'=>$table?->id,'assigned_user_id'=>$this->employee->id,'status'=>$isDone?'closed':'open','workflow_status'=>$workflow,
                'order_type'=>$type,'customer_name'=>$type==='dine_in'?null:'Demo Guest '.($i+1),'customer_phone'=>$type==='dine_in'?null:'+34 611 200 '.($i+1),
                'notes'=>'Client demo order '.($i+1),'kitchen_status'=>$workflow==='preparing'?'in_preparation':($workflow==='ready'?'ready':($isDone?'completed':'pending')),
                'payment_status'=>$workflow==='completed'?'paid':($i===7?'partial':'unpaid'),'total'=>0,'discount_amount'=>$discount,'service_charge'=>$service,
                'completed_at'=>$workflow==='completed'?now()->subDays($days)->setTime(13+$i,15):null,'cancelled_at'=>$workflow==='cancelled'?now()->subDay():null,
                'inventory_deducted_at'=>$workflow==='completed'?now()->subDays($days):null,'created_at'=>now()->subDays($days)->setTime(12+$i,0),'updated_at'=>now()->subDays($days)->setTime(14+$i,0)]);
            $subtotal=0;
            foreach($lines as [$menuIndex,$quantity]) {$item=$this->menu[$menuIndex];$line=(float)$item->price*$quantity;$subtotal+=$line;$order->items()->create(['menu_item_id'=>$item->id,'name'=>$item->name,'quantity'=>$quantity,'unit_price'=>$item->price,'line_total'=>$line,'note'=>$i===6?'Client demo: serve together':null,'station'=>$item->station,'allergens'=>$item->allergens,'status'=>$workflow==='ready'?'ready':($workflow==='preparing'?'in_preparation':'pending')]);}
            $order->update(['total'=>max(0,$subtotal-$discount+$service)]);
            if($workflow==='completed') $order->payments()->create(['cash_register_session_id'=>$this->sessions[min($days,4)]->id,'method'=>$methods[$i],'transaction_type'=>'payment','amount'=>$order->total,'reference'=>'CLIENT-DEMO-PAY-'.($i+1),'user_id'=>$this->employee->id,'paid_at'=>$order->completed_at]);
            elseif($i===7) $order->payments()->create(['cash_register_session_id'=>$this->sessions[4]->id,'method'=>'online','transaction_type'=>'payment','amount'=>5,'reference'=>'CLIENT-DEMO-PARTIAL','user_id'=>$this->employee->id,'paid_at'=>now()]);
        }
        $paid=Order::where('notes','Client demo order 2')->first()?->payments()->first();
        if($paid) $paid->order->payments()->updateOrCreate(['reference'=>'CLIENT-DEMO-REFUND'],['cash_register_session_id'=>$this->sessions[4]->id,'method'=>$paid->method,'transaction_type'=>'refund','refunded_payment_id'=>$paid->id,'amount'=>-3,'user_id'=>$this->employee->id,'paid_at'=>now()]);
    }

    private function seedFinance(): void
    {
        foreach(range(1,8) as $i) {
            Income::updateOrCreate(['description'=>'Client demo income '.$i],['amount'=>110+$i*37,'type'=>'sale','method'=>$i%2?'cash':'card','date'=>today()->subDays($i%5),'user_id'=>$this->employee->id]);
            Expense::updateOrCreate(['description'=>'Client demo expense '.$i],['amount'=>22+$i*11,'category'=>['Food','Beverages','Utilities','Maintenance'][$i%4],'method'=>$i%2?'cash':'card','date'=>today()->subDays($i%5),'user_id'=>$this->employee->id]);
        }
        foreach(range(1,5) as $i) Cashout::updateOrCreate(['notes'=>'Client demo cashout '.$i],['date'=>today()->subDays($i),'opening_cash'=>150,'sales_cash'=>300+$i*25,'expenses_cash'=>40+$i*5,'closing_cash'=>410+$i*20,'expected_cash'=>410+$i*20,'difference'=>0,'user_id'=>$this->employee->id]);
    }

    private function seedStaffActivity(): void
    {
        StaffSchedule::where('notes','like','Client demo shift%')->delete();
        StaffAttendance::where('notes','like','Client demo attendance%')->delete();
        foreach(range(0,6) as $i) StaffSchedule::create(['user_id'=>$this->employee->id,'shift_date'=>today()->addDays($i),'starts_at'=>$i%2?'17:00':'11:00','ends_at'=>$i%2?'23:00':'16:00','area'=>['Main Room','Terrace','Bar'][$i%3],'notes'=>'Client demo shift '.($i+1)]);
        foreach(range(1,5) as $i) StaffAttendance::create(['user_id'=>$this->employee->id,'clocked_in_at'=>now()->subDays($i)->setTime(11,0),'clocked_out_at'=>now()->subDays($i)->setTime(19,0),'break_minutes'=>30,'notes'=>'Client demo attendance '.$i]);
    }

    private function seedRestaurantExceptions(): void
    {
        foreach(range(1,5) as $i) RestaurantException::updateOrCreate(['description'=>'Client demo service exception '.$i],['date'=>today()->addDays(20+$i),'is_closed'=>$i===5,'open_time_1'=>$i===5?null:'12:00','close_time_1'=>$i===5?null:'23:00','max_party_size'=>8+$i]);
    }
}
