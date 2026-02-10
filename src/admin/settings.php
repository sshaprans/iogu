<?php
// src/admin/settings.php
require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../core/logger.php';

Auth::requireLogin();
$db = Database::getInstance()->getConnection();

$configGroups = [
    'general' => [
        'site_title' => ['label' => 'Заголовок сайту', 'type' => 'text', 'translate' => true],
        'site_alt_name' => ['label' => 'Альтернативна назва (скорочена)', 'type' => 'text', 'translate' => true],
        'meta_description' => ['label' => 'Meta Description (SEO)', 'type' => 'textarea', 'translate' => true],
    ],
    'Контакти' => [
        'contact_phones' => ['label' => 'Телефони', 'type' => 'repeater', 'fields' => ['value' => 'Номер', 'label' => 'Підпис (напр. Приймальня)'], 'translate' => true],
        'contact_emails' => ['label' => 'Email адреси', 'type' => 'repeater', 'fields' => ['value' => 'Email', 'label' => 'Підпис'], 'translate' => true],
        'contact_addresses' => ['label' => 'Адреси', 'type' => 'repeater', 'fields' => ['value' => 'Адреса', 'label' => 'Підпис (напр. Головний офіс)'], 'translate' => true],
        'map_zoom' => ['label' => 'Масштаб карти (Zoom)', 'type' => 'text', 'translate' => false, 'hint' => 'Число від 1 до 20 (напр. 15)'],
    ],
    'social' => [
        'social_facebook' => ['label' => 'Facebook', 'type' => 'text', 'translate' => false],
        'social_instagram' => ['label' => 'Instagram', 'type' => 'text', 'translate' => false],
        'social_telegram' => ['label' => 'Telegram', 'type' => 'text', 'translate' => false],
        'social_viber' => ['label' => 'Viber', 'type' => 'text', 'translate' => false],
        'social_whatsapp' => ['label' => 'WhatsApp', 'type' => 'text', 'translate' => false],
        'social_youtube'  => ['label' => 'YouTube', 'type' => 'text', 'translate' => false],
    ],
    'Інтеграції (API)' => [
        'gemini_api_key' => ['label' => 'Gemini AI Key', 'type' => 'code', 'hint' => 'Починається з AIza...'],
        'google_analytics_id' => ['label' => 'Google Analytics ID', 'type' => 'code', 'hint' => 'G-XXXXXXXX'],
        'google_maps_key' => ['label' => 'Google Maps API Key', 'type' => 'code', 'hint' => 'Починається з AIza... (потрібен Maps JavaScript API)'],
        'recaptcha_site_key' => ['label' => 'reCAPTCHA Site Key', 'type' => 'code', 'hint' => 'Публічний ключ (v2 Checkbox)'],
        'recaptcha_secret_key' => ['label' => 'reCAPTCHA Secret Key', 'type' => 'code', 'hint' => 'Секретний ключ (нікому не показувати)'],
    ]
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ($configGroups as $group => $fields) {
            foreach ($fields as $key => $cfg) {
                $valUk = $_POST[$key . '_uk'] ?? '';
                $valEn = ($cfg['translate'] ?? false) ? ($_POST[$key . '_en'] ?? '') : $valUk;

                if ($cfg['type'] === 'repeater') {
                    $jsonUk = isset($_POST[$key . '_uk_json']) ? $_POST[$key . '_uk_json'] : '[]';
                    $jsonEn = isset($_POST[$key . '_en_json']) ? $_POST[$key . '_en_json'] : '[]';
                    $valUk = $jsonUk;
                    $valEn = $jsonEn;
                }

                $stmt = $db->prepare("INSERT INTO settings (key_name, value_uk, value_en, description) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE value_uk = ?, value_en = ?");
                $stmt->execute([$key, $valUk, $valEn, $cfg['label'], $valUk, $valEn]);
            }
        }
        Logger::log('update', 'settings', 0, "Оновлено налаштування");
        $message = "Налаштування збережено!";
    } catch (Exception $e) {
        $error = "Помилка: " . $e->getMessage();
    }
}

// GET
$currentSettings = [];
$stmt = $db->query("SELECT * FROM settings");
while ($row = $stmt->fetch()) {
    $currentSettings[$row['key_name']] = [
        'uk' => $row['value_uk'],
        'en' => $row['value_en']
    ];
}

$pageTitle = 'Глобальні налаштування';
require_once __DIR__ . '/includes/header.php';
?>

    <style>
        .lang-tab { font-size: 0.8em; font-weight: bold; padding: 2px 8px; border-radius: 4px; background: #eee; cursor: pointer; color: #777; }
        .lang-tab.active { background: #3498db; color: white; }
        .btn-remove { background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
        .form-section { margin-bottom: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .section-title { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.2em; }
        .trans-btn { border: none; background: none; cursor: pointer; color: #3498db; font-size: 0.8em; margin-left: 5px; }
        .trans-btn:hover { text-decoration: underline; }
    </style>

    <div class="header">
        <h1><?= $pageTitle?></h1>
    </div>

<?php if (isset($message)): ?>
    <div style="background:#d5f5e3; color:#27ae60; padding:15px; border-radius:4px; margin-bottom:20px;"><?= $message ?></div>
<?php endif; ?>

    <form method="POST" style="max-width: 900px;">
        <?php foreach ($configGroups as $groupName => $fields): ?>
            <div class="form-section">
                <h3 class="section-title"><?= $groupName ?></h3>
                <?php foreach ($fields as $key => $cfg):
                    $valUk = $currentSettings[$key]['uk'] ?? '';
                    $valEn = $currentSettings[$key]['en'] ?? '';
                    $isTranslate = $cfg['translate'] ?? false;
                    $hint = $cfg['hint'] ?? '';
                    ?>
                    <div class="form-group">
                        <label class="form-label">
                            <?= $cfg['label'] ?>
                            <?php if ($hint): ?>
                                <span class="hint" style="font-weight:normal; margin-left:5px;">
                                    (<?= $hint ?>)
                                </span><?php endif; ?>
                        </label>
                        <?php if (!$isTranslate): ?>
                            <input type="text" name="<?= $key ?>_uk" class="form-control" value="<?= htmlspecialchars($valUk) ?>">
                        <?php elseif ($cfg['type'] === 'repeater'): ?>
                            <div class="tabs">
                                <button type="button" class="tab-btn active" onclick="switchTab(this, '<?= $key ?>_uk_wrap')">UK</button>
                                <button type="button" class="tab-btn" onclick="switchTab(this, '<?= $key ?>_en_wrap')">EN</button>
                            </div>
                            <div id="<?= $key ?>_uk_wrap" class="tab-content active">
                                <div class="repeater-container" id="<?= $key ?>_uk_list"></div>
                                <button type="button" class="btn btn-gray" onclick="addRepeaterItem('<?= $key ?>_uk_list', '<?= $cfg['fields']['value'] ?>', '<?= $cfg['fields']['label'] ?>')">+ Додати</button>
                                <input type="hidden" name="<?= $key ?>_uk_json" id="<?= $key ?>_uk_json" value="<?= htmlspecialchars($valUk) ?>">
                            </div>
                            <div id="<?= $key ?>_en_wrap" class="tab-content">
                                <div style="margin-bottom:10px;">
                                    <button type="button" class="btn-translate-manual" onclick="copyRepeaterStructure('<?= $key ?>_uk_list', '<?= $key ?>_en_list', true)">⬇ Скопіювати структуру з UK (з автоперекладом)</button>
                                </div>
                                <div class="repeater-container" id="<?= $key ?>_en_list"></div>
                                <button type="button" class="btn btn-gray" onclick="addRepeaterItem('<?= $key ?>_en_list', '<?= $cfg['fields']['value'] ?>', '<?= $cfg['fields']['label'] ?>')">+ Додати</button>
                                <input type="hidden" name="<?= $key ?>_en_json" id="<?= $key ?>_en_json" value="<?= htmlspecialchars($valEn) ?>">
                            </div>
                        <?php elseif ($cfg['type'] === 'textarea'): ?>
                            <div class="form-row">
                                <div>
                                    <div class="form-label-row"><span class="lang-tab active">UK</span></div>
                                    <textarea name="<?= $key ?>_uk" id="<?= $key ?>_uk" class="form-control auto-translate" data-sync-id="<?= $key ?>" data-lang="uk"><?= htmlspecialchars($valUk) ?></textarea>
                                </div>
                                <div>
                                    <div class="form-label-row"><span class="lang-tab">EN</span><button type="button" class="trans-btn" onclick="manualTranslate('<?= $key ?>_uk', '<?= $key ?>_en', 'uk', 'en')">↻ Перекласти</button></div>
                                    <textarea name="<?= $key ?>_en" id="<?= $key ?>_en" class="form-control auto-translate" data-sync-id="<?= $key ?>" data-lang="en"><?= htmlspecialchars($valEn) ?></textarea>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="form-row">
                                <div>
                                    <div class="form-label-row"><span class="lang-tab active">UK</span></div>
                                    <input type="text" name="<?= $key ?>_uk" id="<?= $key ?>_uk" class="form-control auto-translate" data-sync-id="<?= $key ?>" data-lang="uk" value="<?= htmlspecialchars($valUk) ?>">
                                </div>
                                <div>
                                    <div class="form-label-row"><span class="lang-tab">EN</span><button type="button" class="trans-btn" onclick="manualTranslate('<?= $key ?>_uk', '<?= $key ?>_en', 'uk', 'en')">↻ Перекласти</button></div>
                                    <input type="text" name="<?= $key ?>_en" id="<?= $key ?>_en" class="form-control auto-translate" data-sync-id="<?= $key ?>" data-lang="en" value="<?= htmlspecialchars($valEn) ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <div style="position: sticky; bottom: 20px; background: white; padding: 15px; border-top: 1px solid #eee; text-align: right; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); border-radius: 8px;">
            <button type="submit" class="btn btn-green" style="padding: 12px 25px; font-size: 1.1em;" onclick="serializeRepeaters()">Зберегти всі налаштування</button>
        </div>
    </form>

    <script>
        function switchTab(btn, targetId) {
            const parent = btn.parentElement;
            parent.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const wrapper = parent.parentElement;
            wrapper.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            document.getElementById(targetId).style.display = 'block';
        }

        function addRepeaterItem(containerId, placeholderVal, placeholderLabel, data = {value: '', label: ''}) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'repeater-item';
            const uid = Math.random().toString(36).substr(2, 9);
            const valId = `val_${uid}`;
            const labelId = `lbl_${uid}`;
            div.innerHTML = `<div style="flex-grow:1; display:flex; gap:10px;">
                                <input type="text" class="form-control repeater-val" id="${valId}" placeholder="${placeholderVal}" value="${data.value}">
                                <input type="text" class="form-control repeater-label" id="${labelId}" placeholder="${placeholderLabel}" value="${data.label}">
                            </div>
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>`;
            container.appendChild(div);
        }
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[type="hidden"][name$="_json"]').forEach(input => {
                const containerId = input.id.replace('_json', '_list');
                try {
                    const data = JSON.parse(input.value || '[]');
                    const fieldKey = input.name.replace('_uk_json', '').replace('_en_json', '');
                    let phVal = 'Значення', phLbl = 'Підпис';

                    if (fieldKey.includes('phone')) {
                        phVal = 'Номер'; phLbl = 'Підпис';
                    }

                    if (fieldKey.includes('email')) {
                        phVal = 'Email';
                        phLbl = 'Підпис';
                    }

                    if (fieldKey.includes('address')) {
                        phVal = 'Адреса';
                        phLbl = 'Підпис';
                    }
                    data.forEach(item => addRepeaterItem(containerId, phVal, phLbl, item));
                }
                catch (e) {
                    console.error('JSON Parse error', e);
                }
            });
            document.querySelectorAll('.tabs').forEach(t => {
                const firstBtn = t.querySelector('.tab-btn');
                if(firstBtn) {
                    const wrapper = t.parentElement;
                    const ukWrap = wrapper.querySelector('[id$="_uk_wrap"]');

                    if(ukWrap) ukWrap.style.display = 'block';
                    const enWrap = wrapper.querySelector('[id$="_en_wrap"]');
                    if(enWrap) enWrap.style.display = 'none';
                }
            });
            document.querySelectorAll('.auto-translate').forEach(input => {
                input.addEventListener('blur', async function() {
                    const syncId = this.dataset.syncId;
                    const currentLang = this.dataset.lang;
                    const targetLang = currentLang === 'uk' ? 'en' : 'uk';
                    const targetInput = document.getElementById(`${syncId}_${targetLang}`);
                    if (targetInput && this.value.trim() !== '' && targetInput.value.trim() === '') {
                        await performTranslate(this.value, targetInput, currentLang, targetLang);
                    }
                });
            });
        });
        function serializeRepeaters() {
            document.querySelectorAll('.repeater-container').forEach(container => {
                const items = [];
                container.querySelectorAll('.repeater-item').forEach(item => {
                    const val = item.querySelector('.repeater-val').value;
                    const lbl = item.querySelector('.repeater-label').value;
                    if (val) items.push({
                        value: val,
                        label: lbl
                    });
                });
                const inputId = container.id.replace('_list', '_json');
                document.getElementById(inputId).value = JSON.stringify(items);
            });
        }
        async function copyRepeaterStructure(sourceId, targetId, translate = false) {
            const source = document.getElementById(sourceId);
            const target = document.getElementById(targetId);
            target.innerHTML = '';
            const items = source.querySelectorAll('.repeater-item');
            document.body.style.cursor = 'wait'; for (const item of items) {
                const val = item.querySelector('.repeater-val').value;
                let lbl = item.querySelector('.repeater-label').value;
                if (translate && lbl.trim() !== '') {
                    try {
                        const res = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(lbl)}&langpair=uk|en`);
                        const data = await res.json();
                        if (data.responseStatus === 200) {
                            lbl = data.responseData.translatedText;
                        }
                    } catch(e) {
                        console.error('Translate error', e);
                    }
                } addRepeaterItem(targetId, '', '', {
                    value: val,
                    label: lbl
                });
            } document.body.style.cursor = 'default';
            const wrap = target.parentElement;
            wrap.style.display = 'block';
            wrap.previousElementSibling.style.display = 'none';
            const tabs = wrap.parentElement.querySelector('.tabs');
            tabs.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            tabs.children[1].classList.add('active');
        }
        async function manualTranslate(sourceId, targetId, fromLang, toLang) {
            const text = document.getElementById(sourceId).value.trim();
            const target = document.getElementById(targetId);
            if (!text) return alert('Поле пусте!');
            await performTranslate(text, target, fromLang, toLang);
        }
        async function performTranslate(text, targetElement, fromLang, toLang) {
            targetElement.style.opacity = '0.6';
            document.body.style.cursor = 'wait';
            try {
                const res = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=${fromLang}|${toLang}`);
                const data = await res.json();
                if (data.responseStatus === 200) {
                    targetElement.value = data.responseData.translatedText;
                    targetElement.style.backgroundColor = '#d5f5e3';
                    setTimeout(() => targetElement.style.backgroundColor = '', 1000);
                }
            }
            catch (e) {
                console.error(e);
            }
            finally {
                targetElement.style.opacity = '1';
                document.body.style.cursor = 'default';
            }
        }
    </script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>