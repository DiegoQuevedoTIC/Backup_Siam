<div>
    <hr>
    <br>
    {{ $this->table }}


    <script>
        document.addEventListener('refresh-table', () => {
            @this.call('$refresh');
        });
    </script>
</div>
