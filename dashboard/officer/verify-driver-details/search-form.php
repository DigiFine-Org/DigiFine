<form action="index.php" method="GET">
    <div style="margin-bottom: 15px;">
        <label>Search By:</label>
        <div style="display: flex; gap: 15px; margin-top: 5px;">
            <label>
                <input type="radio" name="search_type" value="license" checked> Driver License ID
            </label>
            <label>
                <input type="radio" name="search_type" value="nic"> NIC
            </label>
        </div>
    </div>
    <input name="query" required type="search" class="input"
        placeholder="Enter Driver License ID (B1234567) or NIC Number">
    <button class="btn margintop">Search</button>
</form>