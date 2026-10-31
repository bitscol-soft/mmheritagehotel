#!/usr/bin/env python3
"""One-shot L11-prep cleanup transformations (run from repo root).

1. Route files: 'Ctrl@method' -> [Ctrl::class, 'method'], 'Ctrl' resources -> Ctrl::class
2. Blade views: laravelcollective Form::select/Form::number -> plain HTML
3. Controllers: unique_with validation rules -> Rule::unique()->where()
4. Models: $dates -> $casts
5. composer.json/lock: drop unused packages (content-hash recomputed)
"""
import json, re, sys, hashlib, os

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)
report = []


def rw(path, fn):
    src = open(path, encoding='utf-8').read()
    out, n = fn(src)
    if out != src:
        open(path, 'w', encoding='utf-8').write(out)
    report.append(f"  {path}: {n} replacement(s)")


# ---------------------------------------------------------------- 1. routes
ROUTE_MAP = {
    'module/GeneralStore/routes/web_generalstore.php': 'Module\\GeneralStore\\Controllers',
    'module/Permission/routes/web_permission.php': 'Module\\Permission\\Controllers',
}

for path, ns in ROUTE_MAP.items():
    def conv(src, ns=ns):
        n = 0
        classes = set()
        # 'Ctrl@method' -> [Ctrl::class, 'method']
        def action(m):
            nonlocal n
            n += 1
            classes.add(m.group(1))
            return f"[{m.group(1)}::class, '{m.group(2)}']"
        src = re.sub(r"'([A-Za-z]+Controller)@(\w+)'", action, src)
        # Route::resource('x', 'Ctrl') -> Route::resource('x', Ctrl::class)
        def resource(m):
            nonlocal n
            n += 1
            classes.add(m.group(2))
            return f"{m.group(1)}{m.group(2)}::class"
        src = re.sub(r"(Route::(?:resource|apiResource)\('[^']+',\s*)'([A-Za-z]+Controller)'",
                     resource, src)
        # verify each class file exists, then add imports
        missing = []
        for c in sorted(classes):
            sub = ns.replace('\\', '/')
            disk = sub.replace('Module/', 'module/', 1)  # PSR-4 root dir
            if not os.path.exists(f"{disk}/{c}.php") and not os.path.exists(f"{sub}/{c}.php"):
                missing.append(c)
        if missing:
            sys.exit(f"MISSING controllers in {path}: {missing}")
        if classes:
            use_block = '\n'.join(f"use {ns}\\{c};" for c in sorted(classes))
            if f"use {ns}\\" not in src:
                src = src.replace("use Illuminate\\Support\\Facades\\Route;",
                                  "use Illuminate\\Support\\Facades\\Route;\n" + use_block, 1)
            else:
                # insert after the last existing use of that namespace
                pass
        return src, n
    rw(path, conv)


# ---------------------------------------------------------------- 2. blades
def select_block(name, collection, selected, attrs, placeholder, indent):
    """Build plain-HTML <select> equivalent of Form::select()."""
    sel = f"old('{name}', {selected})" if selected else f"old('{name}')"
    i = ' ' * indent
    opts = (f"\n{i}    <option value=\"\">{placeholder}</option>"
            f"\n{i}    @foreach (${collection} as $optionValue => $optionLabel)"
            f"\n{i}        <option value=\"{{{{ $optionValue }}}}\" "
            f"@if({sel} == $optionValue) selected @endif>{{{{ $optionLabel }}}}</option>"
            f"\n{i}    @endforeach")
    return f"<select name=\"{name}\" {attrs}>{opts}\n{i}</select>"


BLADES = []

# --- single-line currency selects (3 files) ---
for path, selected in [
    ('module/Hotel/views/currency-conversions/create.blade.php', None),
    ('module/Hotel/views/currency-conversions/edit.blade.php', '$currencyConversion->currency_id'),
    ('module/Restaurant/views/kitchen/edit.blade.php', '$currencyConversion->currency_id'),
]:
    def conv(src, selected=selected):
        pat = re.compile(r"\{!!\s*Form::select\('currency_id', \$currencies, [^,]+,"
                         r" \['class' => 'form-control chosen-select', 'id' => 'currencyId',"
                         r" 'placeholder' => 'Select Currency', 'required'\]\)\s*!!\}")
        new = select_block('currency_id', 'currencies', selected,
                           'id="currencyId" class="form-control chosen-select" required',
                           'Select Currency', 0)
        out, n = pat.subn(lambda m: new, src)
        return out, n
    rw(path, conv)

# --- room name selects (Hotel rooms create/edit) ---
for path, selected in [
    ('module/Hotel/views/rooms/create.blade.php', None),
    ('module/Hotel/views/rooms/edit.blade.php', '$room->room_category'),
]:
    def conv(src, selected=selected):
        n = 0
        # drop the stale commented-out collective line
        src2 = re.sub(r"[ \t]*\{\{--\s*\{!!\s*Form::select\('name'.*?--\}\}\s*\n", "", src, flags=re.S)
        if src2 != src:
            n += 1
        src = src2
        pat = re.compile(r"\{!!\s*Form::select\('name', \$room_category, [^,]+, \[\s*"
                         r"'class' => 'form-control chosen-select',\s*"
                         r"'id' => 'category',\s*"
                         r"'placeholder' => 'Select Room Name',\s*"
                         r"'onchange' => 'checkRoomNumberByCategory\(this\)',\s*"
                         r"'required',\s*\]\)\s*!!\}", re.S)
        new = select_block('name', 'room_category', selected,
                           'id="category" class="form-control chosen-select" '
                           'onchange="checkRoomNumberByCategory(this)" required',
                           'Select Room Name', 0)
        out, k = pat.subn(lambda m: new, src)
        return out, n + k
    rw(path, conv)

# --- hall_category selects (BanquetHall hall/rooms create/edit) ---
for path, selected, placeholder in [
    ('module/BanquetHall/views/hall/rooms/create.blade.php', None, 'Select Category Name'),
    ('module/BanquetHall/views/hall/rooms/edit.blade.php', '$room->hall_category', 'Select Category'),
]:
    def conv(src, selected=selected, placeholder=placeholder):
        n = 0
        src2 = re.sub(r"[ \t]*\{\{--\s*\{!!\s*Form::select\('name'.*?--\}\}\s*\n", "", src, flags=re.S)
        if src2 != src:
            n += 1
        src = src2
        pat = re.compile(r"\{!!\s*Form::select\('hall_category', \$room_category, [^,]+, \[\s*"
                         r"'class' => 'form-control chosen-select',\s*"
                         r"'id' => 'category',\s*"
                         r"'placeholder' => '[^']*',\s*"
                         r"'onchange' => 'checkRoomNumberByCategory\(this\)',\s*"
                         r"'required',\s*\]\)\s*!!\}", re.S)
        new = select_block('hall_category', 'room_category', selected,
                           'id="category" class="form-control chosen-select" '
                           'onchange="checkRoomNumberByCategory(this)" required',
                           placeholder, 0)
        out, k = pat.subn(lambda m: new, src)
        return out, n + k
    rw(path, conv)

# --- room_number[] empty select (Hotel booking script) ---
def conv_roomnum(src):
    pat = re.compile(r"\{!!\s*Form::select\('room_number\[\]', \[\], null, \[\s*"
                     r"'class' => 'form-cotnrol room_number',\s*"
                     r"'placeholder' => 'Select Room Number',\s*\]\)\s*!!\}", re.S)
    new = ('<select name="room_number[]" class="form-cotnrol room_number" '
           'placeholder="Select Room Number">\n'
           '                                <option value="">Select Room Number</option>\n'
           '                            </select>')
    return pat.subn(lambda m: new, src)
rw('module/Hotel/views/booking/_script/script.blade.php', conv_roomnum)

# --- Form::number -> <input type="number"> ---
NUMBER_PATTERNS = [
    # (regex for the whole Form::number call, replacement)
    (r"\{!!\s*Form::number\('infant\[\]', \$data->infant_count, \[\s*"
     r"'class' => 'form-control text-right input-infant',\s*'min' => 0,\s*\]\)\s*!!\}",
     '<input type="number" name="infant[]" value="{{ $data->infant_count }}" '
     'class="form-control text-right input-infant" min="0">'),
    (r"\{!!\s*Form::number\('night\[\]', \$data->night_count \?\? 1, \[\s*"
     r"'class' => 'form-control text-right night_count',\s*'readonly',\s*\]\)\s*!!\}",
     '<input type="number" name="night[]" value="{{ $data->night_count ?? 1 }}" '
     'class="form-control text-right night_count" readonly>'),
    (r"\{!!\s*Form::number\('discount\[\]', calculateCurrencyAmount\(\$data->discount_amount, 1\), \[\s*"
     r"'class' => 'form-control text-right discount',\s*'type' => 'number',\s*'min' => 0,\s*\]\)\s*!!\}",
     '<input type="number" name="discount[]" '
     'value="{{ calculateCurrencyAmount($data->discount_amount, 1) }}" '
     'class="form-control text-right discount" min="0">'),
    (r"\{!!\s*Form::number\('infant\[\]', 0, \['class' => 'form-control text-right input-infant', 'min' => 0\]\)\s*!!\}",
     '<input type="number" name="infant[]" value="0" '
     'class="form-control text-right input-infant" min="0">'),
    (r"\{!!\s*Form::number\('child\[\]', 0, \['class' => 'form-control text-right input-child', 'min' => 0\]\)\s*!!\}",
     '<input type="number" name="child[]" value="0" '
     'class="form-control text-right input-child" min="0">'),
    (r"\{!!\s*Form::number\('night\[\]', 1, \['class' => 'form-control text-right night_count', 'readonly'\]\)\s*!!\}",
     '<input type="number" name="night[]" value="1" '
     'class="form-control text-right night_count" readonly>'),
]
for nm in ['discount[]', 'rest_item_discount[]', 'input_item_discount[]']:
    NUMBER_PATTERNS.append(
        ('\\{!!\\s*Form::number\\(\'' + re.escape(nm) + '\', 0, \\[\\s*'
         r"'class' => 'form-control text-right discount',\s*'type' => 'number',\s*'min' => 0,\s*\]\)\s*!!\}",
         f'<input type="number" name="{nm}" value="0" '
         'class="form-control text-right discount" min="0">'))

NUMBER_FILES = [
    'module/Hotel/views/booking/edit.blade.php',
    'module/Hotel/views/booking/_script/script.blade.php',
    'module/BanquetHall/views/hall_booking/_script/script.blade.php',
]

def make_number_conv():
    def conv(src):
        n = 0
        for pat, rep in NUMBER_PATTERNS:
            src, k = re.subn(pat, lambda m: rep, src, flags=re.S)
            n += k
        return src, n
    return conv

for path in NUMBER_FILES:
    rw(path, make_number_conv())


# ---------------------------------------------------------------- 3. unique_with
def rule_unique(table, column, where_col, ignore=None):
    ig = f"->ignore({ignore})" if ignore else ""
    return (f"[Rule::unique('{table}', '{column}')->where('{where_col}', "
            f"$request->{where_col}){ig}]")

UNIQUE_CONVS = [
    ('app/Http/Controllers/SupplierController.php', [
        ("'name'              => 'unique_with:suppliers, name, supplier_type_id',",
         f"'name'              => {rule_unique('suppliers', 'name', 'supplier_type_id')},"),
        ("'name'              => 'unique_with:suppliers, name, supplier_type_id,' . $Supplier->id,",
         f"'name'              => {rule_unique('suppliers', 'name', 'supplier_type_id', '$Supplier->id')},"),
    ]),
    ('module/GeneralStore/Controllers/SupplierController.php', [
        ("'name'              => 'unique_with:suppliers, name, supplier_type_id',",
         f"'name'              => {rule_unique('suppliers', 'name', 'supplier_type_id')},"),
        ("'name'              => 'unique_with:suppliers, name, supplier_type_id,' . $Supplier->id,",
         f"'name'              => {rule_unique('suppliers', 'name', 'supplier_type_id', '$Supplier->id')},"),
    ]),
    ('module/GeneralStore/Controllers/ItemController.php', [
        ("'name'         => 'unique_with:items, name, company_id',",
         f"'name'         => {rule_unique('items', 'name', 'company_id')},"),
    ]),
]

for path, subs in UNIQUE_CONVS:
    def conv(src, subs=subs):
        n = 0
        for old, new in subs:
            if old not in src:
                sys.exit(f"pattern not found in {path}: {old}")
            src = src.replace(old, new)
            n += 1
        # ensure Rule import
        if 'use Illuminate\\Validation\\Rule;' not in src:
            m = re.search(r"^use [^\n]+;\n", src, re.M)
            lines = list(re.finditer(r"^use [^\n]+;\n", src, re.M))
            last = lines[-1]
            src = src[:last.end()] + "use Illuminate\\Validation\\Rule;\n" + src[last.end():]
            n += 1
        return src, n
    rw(path, conv)


# ---------------------------------------------------------------- 4. $dates -> $casts
for path in ['module/BanquetHall/Models/Service/HotelServiceSaleItem.php',
             'module/HotelService/Models/Service/HotelServiceSaleItem.php']:
    def conv(src):
        pat = re.compile(r"protected \$dates = \[\s*'delivered_at'\s*\];")
        new = "protected $casts = [\n        'delivered_at' => 'datetime'\n    ];"
        return pat.subn(lambda m: new, src)
    rw(path, conv)


# ---------------------------------------------------------------- 5. composer
def php_json_encode(obj):
    """Approximate PHP json_encode() (escapes slashes, unicode)."""
    s = json.dumps(obj, separators=(',', ':'), ensure_ascii=True)
    s = s.replace('/', '\\/')
    return s

def content_hash(composer_text):
    content = json.loads(composer_text)
    relevant_keys = ['name', 'version', 'require', 'require-dev', 'conflict',
                     'replace', 'provide', 'minimum-stability', 'prefer-stable',
                     'repositories', 'extra']
    relevant = {}
    for k in relevant_keys:
        if k in content:
            relevant[k] = content[k]
    if 'config' in content and 'platform' in content.get('config', {}):
        relevant.setdefault('config', {})['platform'] = content['config']['platform']
    relevant = dict(sorted(relevant.items()))
    return hashlib.md5(php_json_encode(relevant).encode()).hexdigest()

DROP = ['doctrine/dbal', 'felixkiss/uniquewith-validator',
        'laravelcollective/html', 'livewire/livewire']

ctext = open('composer.json', encoding='utf-8').read()
lock = json.load(open('composer.lock', encoding='utf-8'))
old_hash = lock.get('content-hash')
calc_old = content_hash(ctext)
print(f"content-hash check: lock={old_hash} calculated={calc_old} "
      f"{'MATCH' if old_hash == calc_old else 'MISMATCH'}")
if old_hash != calc_old:
    sys.exit("content-hash algorithm mismatch — aborting composer changes")

lines = ctext.splitlines(keepends=True)
kept = []
removed = set()
for ln in lines:
    m = re.match(r'\s*"([^"]+)":\s*"[^"]*",?\s*$', ln)
    if m and m.group(1) in DROP:
        removed.add(m.group(1))
        continue
    kept.append(ln)
for p in DROP:
    if p in removed:
        report.append(f"  composer.json: removed {p}")
    else:
        report.append(f"  composer.json: {p} NOT FOUND as removable line")
new_ctext = ''.join(kept)
json.loads(new_ctext)  # sanity: still valid JSON
open('composer.json', 'w', encoding='utf-8').write(new_ctext)
new_hash = content_hash(new_ctext)

before = len(lock['packages'])
lock['packages'] = [pkg for pkg in lock['packages'] if pkg['name'] not in DROP]
lock['packages-dev'] = [pkg for pkg in lock.get('packages-dev', []) if pkg['name'] not in DROP]
lock['content-hash'] = new_hash
report.append(f"  composer.lock: removed {before - len(lock['packages'])} package entries, "
              f"content-hash {old_hash} -> {new_hash}")
json.dump(lock, open('composer.lock', 'w', encoding='utf-8'), indent=4, ensure_ascii=False)
open('composer.lock', 'a', encoding='utf-8').write('\n')

print('\n'.join(report))
print("DONE")
