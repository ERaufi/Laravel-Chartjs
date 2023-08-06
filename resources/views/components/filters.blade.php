<div>
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
    <div class="row">
        <div class="col-md-3">
            <label for="country">Country</label>
            <select class="form-control" id="country">
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="From">From</label>
            <input type="date" id="from" name="from" class="form-control" />
        </div>
        <div class="col-md-3">
            <label for="To">To</label>
            <input type="date" id="to" name="to" class="form-control" />
        </div>
        <div class="col-md-3">
            <input type="button" class="btn btn-success" value="Filter" onclick="getData()" />
        </div>
    </div>
</div>
