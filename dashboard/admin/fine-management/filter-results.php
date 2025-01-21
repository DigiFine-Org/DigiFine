<form method="GET" class="filter-form" id="filter-form" style="display: none;">
    <div class="filter-form-container">
        <div class="field">

            <div class="field">
                <label for="fine_id">Fine ID:</label>
                <input type="text" name="fine_id" id="fine_id" placeholder="Search Fine ID" value="<?= htmlspecialchars($_GET['fine_id'] ?? '') ?>">
            </div>

            <div class="field">
                <label for="police_id">Police ID:</label>
                <input type="text" name="police_id" id="police_id" placeholder="Search Police ID" value="<?= htmlspecialchars($_GET['police_id'] ?? '') ?>">
            </div>

            <div class="field">
                <label for="driver_id">Driver ID:</label>
                <input type="text" name="driver_id" id="driver_id" placeholder="Search Driver ID" value="<?= htmlspecialchars($_GET['driver_id'] ?? '') ?>">
            </div>

            <div class="field">
                <label for="date-from">from:</label>
                <input type="date" name="date-from" id="date-from" value="<?= htmlspecialchars($_GET['date-from'] ?? '') ?>">
            </div>

            <div class="field">
                <label for="date-to">to:</label>
                <input type="date" name="date-to" id="date-to" value="<?= htmlspecialchars($_GET['date-to'] ?? '') ?>">
            </div>


            <div class="field">
                <label for="offence_type">Offence Type:</label>
                <select name="offence_type" id="offence_type">
                    <option value="">--Select--</option>
                    <?php foreach ($offenceTypes as $type): ?>
                        <option value="<?= htmlspecialchars($type['offence_type']) ?>"
                            <?= isset($_GET['offence_type']) && $_GET['offence_type'] == $type['offence_type'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['offence_type']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="offence">Offence:</label>
                <select name="offence" id="offence">
                    <option value="">--Select--</option>
                    <?php foreach ($offences as $offence): ?>
                        <option value="<?= htmlspecialchars($offence['offence']) ?>"
                            <?= isset($_GET['offence']) && $_GET['offence'] == $offence['offence'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($offence['offence']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="fine_status">Fine Status:</label>
                <select name="fine_status" id="fine_status">
                    <option value="">--Select--</option>
                    <?php foreach ($fineStatuses as $status): ?>
                        <option value="<?= htmlspecialchars($status) ?>"
                            <?= isset($_GET['fine_status']) && $_GET['fine_status'] == $status ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="buttons">
                <div class="field">
                    <button class="btn" type="submit">Filter</button>
                </div>
                <div class="field">
                    <a href="index.php" class="btn">Reset</a>
                </div>
                <div class="field">
                    <button class="btn" type="button" onclick="document.getElementById('filter-form').style.display='none';">Hide</button>

                </div>
</form>