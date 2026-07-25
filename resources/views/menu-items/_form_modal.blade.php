@php
    $isEdit = $mode === 'edit';
    $modalState = $isEdit ? 'editModal' : 'addModal';
    $prefix = $isEdit ? 'edit-' : '';
@endphp
<div x-show="{{ $modalState }}" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-3">
    <div class="modal-shade absolute inset-0" @click="{{ $modalState }}=false"></div>
    <div class="modal-panel relative w-full max-w-3xl rounded-2xl" @click.stop @keydown.escape.window="{{ $modalState }}=false">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-[#eadfd2] bg-[#fffdf9]/95 px-4 py-3 backdrop-blur">
            <div><p class="eyebrow">{{ $isEdit ? 'Edit menu item' : 'New menu item' }}</p><h2 class="text-lg font-black text-[#30261f]" @if($isEdit) x-text="editingProduct.name || 'Menu item'" @endif>{{ $isEdit ? 'Menu item' : 'Add Menu Item' }}</h2></div>
            <button type="button" @click="{{ $modalState }}=false" class="text-2xl leading-none text-[#9b8b7e]">&times;</button>
        </div>

        <form @if($isEdit) :action="editingProduct.update_url" @else action="{{ route('menu-items.store') }}" @endif method="POST" enctype="multipart/form-data" class="space-y-3 p-4 text-left">
            @csrf
            @if($isEdit) @method('PUT') <input type="hidden" name="editing_product_id" :value="editingProduct.id"> @endif

            <div class="grid gap-3 sm:grid-cols-[1.4fr_1fr_.65fr]">
                <div><label class="field-label" for="{{ $prefix }}product-name">Product name</label><input id="{{ $prefix }}product-name" name="name" required placeholder="Grilled sea bass" @if($isEdit) x-model="editingProduct.name" @else value="{{ old('name') }}" @endif></div>
                <div><label class="field-label" for="{{ $prefix }}product-category">Category</label><select id="{{ $prefix }}product-category" name="category" required @if($isEdit) x-model="editingProduct.category" @endif>@foreach($categoryOptions as $cat)<option value="{{ $cat }}" @if(!$isEdit && old('category')===$cat) selected @endif>{{ $cat }}</option>@endforeach</select></div>
                <div><label class="field-label" for="{{ $prefix }}product-price">Standard price</label><input id="{{ $prefix }}product-price" type="number" name="price" step="0.01" min="0" required placeholder="0.00" @if($isEdit) x-model="editingProduct.price" @else value="{{ old('price') }}" @endif></div>
            </div>

            <div class="grid gap-3 sm:grid-cols-4">
                <div><label class="field-label">Availability</label><select name="availability_status" @if($isEdit) x-model="editingProduct.availability_status" @endif><option value="available">Available</option><option value="sold_out" @if(!$isEdit && old('availability_status')==='sold_out') selected @endif>Sold out</option></select></div>
                <div><label class="field-label">Menu type</label><select name="item_kind" @if($isEdit) x-model="editingProduct.item_kind" @endif><option value="regular">Regular item</option><option value="lunch_menu" @if(!$isEdit && old('item_kind')==='lunch_menu') selected @endif>Lunch menu</option><option value="promotion" @if(!$isEdit && old('item_kind')==='promotion') selected @endif>Promotion</option><option value="seasonal" @if(!$isEdit && old('item_kind')==='seasonal') selected @endif>Seasonal</option></select></div>
                <div><label class="field-label">Kitchen station</label><select name="station" @if($isEdit) x-model="editingProduct.station" @endif><option value="kitchen">Kitchen</option><option value="bar">Bar</option><option value="desserts">Desserts</option><option value="drinks">Drinks</option></select></div>
                <div><label class="field-label">Display order</label><input type="number" name="sort_order" min="0" max="999" @if($isEdit) x-model="editingProduct.sort_order" @else value="{{ old('sort_order',0) }}" @endif></div>
            </div>

            <div class="rounded-xl border border-[#eadfd2] bg-[#faf5ed] p-3">
                <div class="mb-2 flex items-center justify-between"><strong class="text-xs text-[#554237]">Prices by order type</strong><span class="text-[10px] font-bold text-[#9b8b7e]">Leave blank to use standard price</span></div>
                <div class="grid grid-cols-3 gap-2">
                    <div><label class="field-label">Dine-in</label><input type="number" name="dine_in_price" step="0.01" min="0" placeholder="Standard" @if($isEdit) x-model="editingProduct.dine_in_price" @else value="{{ old('dine_in_price') }}" @endif></div>
                    <div><label class="field-label">Takeaway</label><input type="number" name="takeaway_price" step="0.01" min="0" placeholder="Standard" @if($isEdit) x-model="editingProduct.takeaway_price" @else value="{{ old('takeaway_price') }}" @endif></div>
                    <div><label class="field-label">Delivery</label><input type="number" name="delivery_price" step="0.01" min="0" placeholder="Standard" @if($isEdit) x-model="editingProduct.delivery_price" @else value="{{ old('delivery_price') }}" @endif></div>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <div><label class="field-label">Description</label><textarea name="description" rows="2" placeholder="Short customer-facing description" @if($isEdit) x-model="editingProduct.description" @endif>{{ $isEdit ? '' : old('description') }}</textarea></div>
                <div><label class="field-label">Ingredients</label><textarea name="ingredients" rows="2" placeholder="Beef, brioche, onion, house sauce" @if($isEdit) x-model="editingProduct.ingredients" @endif>{{ $isEdit ? '' : old('ingredients') }}</textarea></div>
            </div>
            <div class="grid gap-3 sm:grid-cols-[1fr_.45fr_.45fr]">
                <div><label class="field-label">Allergens</label><input name="allergens" placeholder="Gluten, milk, nuts" @if($isEdit) x-model="editingProduct.allergens" @else value="{{ old('allergens') }}" @endif></div>
                <div><label class="field-label">Stock</label><input type="number" name="stock_quantity" min="0" placeholder="Not tracked" @if($isEdit) x-model="editingProduct.stock_quantity" @else value="{{ old('stock_quantity') }}" @endif></div>
                <div><label class="field-label">Low-stock at</label><input type="number" name="low_stock_threshold" min="0" @if($isEdit) x-model="editingProduct.low_stock_threshold" @else value="{{ old('low_stock_threshold',5) }}" @endif></div>
            </div>

            <div class="grid items-center gap-3 sm:grid-cols-[1fr_auto]">
                <div class="rounded-xl border border-dashed border-[#dccbbb] bg-white p-3">
                    <label class="field-label">Dish image</label>
                    @if($isEdit)<div x-show="editingProduct.image_url" class="mb-2 flex items-center gap-2"><img :src="editingProduct.image_url || ''" alt="" class="h-10 w-10 rounded-lg object-cover"><label class="text-xs font-bold text-[#79685b]"><input type="checkbox" name="remove_image" value="1" x-model="editingProduct.remove_image"> Remove current image</label></div>@endif
                    <input type="file" name="image" accept="image/*" @if($isEdit) x-ref="editImage" @endif class="max-w-full text-xs text-[#79685b]">
                </div>
                <label class="flex items-center gap-2 rounded-xl border border-[#e4d8cc] bg-white px-3 py-2 text-xs font-black text-[#5f4b3f]"><input type="checkbox" name="is_active" value="1" @if($isEdit) x-model="editingProduct.is_active" @elseif(old('is_active',true)) checked @endif> Show on menu</label>
            </div>

            <div class="sticky bottom-0 -mx-4 -mb-4 flex items-center gap-2 border-t border-[#eadfd2] bg-[#fffdf9]/95 px-4 py-3 backdrop-blur">
                @if($isEdit)<button type="button" @click="if(confirm('Delete this product?')) document.getElementById('delete-product-form').submit()" class="rounded-xl border border-red-200 px-3 py-2.5 text-xs font-black text-red-600">Delete</button>@endif
                <button type="button" @click="{{ $modalState }}=false" class="ml-auto rounded-xl border border-[#dfd1c3] bg-white px-4 py-2.5 text-xs font-black text-[#6d5a4d]">Cancel</button>
                <button class="rounded-xl bg-[#bd5738] px-5 py-2.5 text-xs font-black text-white">{{ $isEdit ? 'Save changes' : 'Create menu item' }}</button>
            </div>
        </form>

        @if($isEdit)
            <section class="border-t border-[#eadfd2] bg-[#faf5ed] p-4">
                <div class="mb-2 flex items-center justify-between"><div><h3 class="text-sm font-black text-[#3b2e26]">Sizes, add-ons & extras</h3><p class="text-[11px] text-[#968679]">Add customer choices and their price change.</p></div><span class="tag tag-special" x-text="(editingProduct.options || []).length + ' choices'"></span></div>
                <form :action="editingProduct.options_url" method="POST" class="grid gap-2 sm:grid-cols-[120px_1fr_110px_auto]">@csrf
                    <select name="type"><option value="size">Size</option><option value="add_on">Add-on</option><option value="extra">Extra</option><option value="variation">Variation</option></select>
                    <input name="name" required placeholder="Large / Extra cheese">
                    <input type="number" name="price_adjustment" step="0.01" value="0" aria-label="Price change">
                    <button class="rounded-xl bg-[#4e3c31] px-3 text-xs font-black text-white">Add choice</button>
                    <input type="hidden" name="is_active" value="1">
                </form>
                <div class="mt-2 flex flex-wrap gap-1.5">
                    <template x-for="option in (editingProduct.options || [])" :key="option.id">
                        <form :action="option.destroy_url" method="POST" class="flex items-center gap-1 rounded-full border border-[#dfd1c3] bg-white py-1 pl-2.5 pr-1 text-[11px] font-bold text-[#59463a]">@csrf @method('DELETE')
                            <span x-text="option.type.replace('_',' ') + ': ' + option.name"></span><span class="text-[#bd5738]" x-text="Number(option.price_adjustment) === 0 ? '' : ((Number(option.price_adjustment)>0?'+':'')+'€'+Number(option.price_adjustment).toFixed(2))"></span><button title="Remove" class="flex h-5 w-5 items-center justify-center rounded-full bg-[#fae9e3] text-red-600">×</button>
                        </form>
                    </template>
                </div>
            </section>
            <form id="delete-product-form" :action="editingProduct.delete_url" method="POST" class="hidden">@csrf @method('DELETE')</form>
        @endif
    </div>
</div>
