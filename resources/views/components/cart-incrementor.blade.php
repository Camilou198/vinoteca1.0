@props(['item', 'action', 'hidden_key'])
<form action="{{ $action }}" method="POST" class="ms-2 inline">
    @csrf
    <input type="hidden" name="wine_id"  value="{{ data_get($item, $hidden_key) }}">
    <button
        type="submit"
        class="bg-green-500 hover:bg-green-700 text-white font-bold py-0 rounded mb-2 py-3 px-3 text-center text-xs"
    >
        +
    </button>
</form>
