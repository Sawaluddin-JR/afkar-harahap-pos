<div>
    <div class="form-row">
        <div class="col-md-7">
            <div class="form-group">
                <label>Kategori Barang</label>
                <select wire:model.live="category" class="form-control">
                    <option value="">All Barang</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>Jumlah Barang</label>
                <select wire:model.live="showCount" class="form-control">
                    <option value="9">9 Barang</option>
                    <option value="15">15 Barang</option>
                    <option value="21">21 Barang</option>
                    <option value="30">30 Barang</option>
                    <option value="">All Barang</option>
                </select>
            </div>
        </div>
    </div>
</div>
