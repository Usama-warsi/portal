
<option value="SalesAgent" {{(isset($_GET['account_type']) && $_GET['account_type'] == 'SalesAgent') ? 'selected' : '' }}>
    {{ __('Sales Agent') }}
</option>
