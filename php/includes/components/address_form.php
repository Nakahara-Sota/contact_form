<th class="table-light">
    <div class="d-flex justify-content-between align-items-center">
        <span>住所</span>
        <span class="badge text-bg-danger fs-6">必須</span>
    </div>
</th>
<td>
    <?php if (isset($errors['postal_code'])): ?>
        <div class="text-danger small fw-bold"><?= $errors['postal_code'] ?></div>
    <?php endif; ?>
    <label for="postal_code" class="text-muted fw-bold small mb-1 d-block">郵便番号: (半角数字とハイフンのみ)</label>
    <div class="d-flex align-items-center gap-2 count-group">
        <input type="text" class="form-control p-postal-code" name="postal_code" id="postal_code" value="<?= htmlspecialchars($old['postal_code'] ?? '') ?>">
        <span class="btn btn-danger btn-sm fw-bold text-nowrap" style="display: none; cursor: pointer;">消去</span>
    </div>
    <?php if (isset($errors['pref'])): ?>
        <div class="text-danger small fw-bold"><?= $errors['pref'] ?></div>
    <?php endif; ?>
    <input type="hidden" class="p-region" value="">
    <div class="row align-items-center mt-1">
        <div class="col-auto">
            <label for="pref" class="text-muted fw-bold small mb-1 d-block">都道府県:</label>
        </div>
        <div class="col-auto">
            <select class="form-select form-select-sm" name="pref" id="pref" autocomplete="address-level1">
                <!-- $selected_idが0（未選択）の時のみselectedになる -->
                <option value="" <?= ($selected_id === 0) ? 'selected' : ''; ?> disabled>--選択してください--</option>
                <?php
                $current_region = null;
                foreach ($prefs as $pref):
                    //地方が変わったらoptgroupを切り替える
                    if ($current_region !== $pref['region_name']):
                        if ($current_region !== null) echo '</optgroup>';
                        echo '<optgroup label="' . htmlspecialchars($pref['region_name']) . '">';
                        $current_region = $pref['region_name'];
                    endif;
                ?>
                    <option value="<?= $pref['id']; ?>" <?= ((int)$pref['id'] == (int)$selected_id) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($pref['name']); ?>
                    </option>
                <?php endforeach; ?>

                <?php if ($current_region !== null) echo '</optgroup>'; ?>

            </select>
        </div>
    </div>
    <div class="count-group">
        <?php if (isset($errors['city'])): ?>
            <div class="text-danger small fw-bold"><?= $errors['city'] ?></div>
        <?php endif; ?>
        <label for="city" class="text-muted fw-bold small mb-1 d-block">
            市区町村: (<span>現在の文字数: <span class="count-view">0</span> / 50文字</span>)
        </label>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="form-control p-locality" name="city" id="city" autocomplete="address-level2" value="<?= htmlspecialchars($old['city'] ?? '') ?>" limit="50" least="1">
            <span class="btn btn-danger btn-sm fw-bold text-nowrap" style="display: none; cursor: pointer;">消去</span>
        </div>
    </div>
    <div class="count-group">
        <?php if (isset($errors['address_line'])): ?>
            <div class="text-danger small fw-bold"><?= $errors['address_line'] ?></div>
        <?php endif; ?>
        <label for="address_line" class="text-muted fw-bold small mb-1 d-block">
            町名番地等: (<span>現在の文字数: <span class="count-view">0</span> / 50文字</span>)
        </label>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="form-control p-street-address" name="address_line" id="address_line" autocomplete="address-level1" value="<?= htmlspecialchars($old['address_line'] ?? '') ?>" limit="50" least="1">
            <span class="btn btn-danger btn-sm fw-bold text-nowrap" style="display: none; cursor: pointer;">消去</span>
        </div>
    </div>
</td>
</tr>