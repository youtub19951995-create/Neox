# ✏️ WHAT TO EDIT - QUICK CHECKLIST

## 🔴 REQUIRED EDITS (MUST DO)

### 1. DATABASE CONNECTION (`db_config.php`)
```php
Line 7:  define('DB_HOST', 'localhost');        // ✏️ Change if remote DB
Line 8:  define('DB_NAME', 'cyborx_bot');       // ✏️ Your database name
Line 9:  define('DB_USER', 'root');             // ✏️ Your DB username
Line 10: define('DB_PASS', '');                 // ✏️ Your DB password
```

### 2. BOT BRANDING (`config.js`)
```javascript
Line 10: BOT_NAME: 'CyborX',                    // ✏️ Your bot name
Line 11: BOT_DOMAIN: 'cyborx.net',              // ✏️ Your domain
Line 12: ADMIN_USERNAME: 'cyborx_admin',        // ✏️ Your admin username
Line 13: SUPPORT_BOT: '@CyborXSupport_bot',     // ✏️ Your support bot
```

### 3. CRYPTO WALLETS (`config.js`)
```javascript
Line 39: BINANCE_ID: '753175553',               // ✏️ Your Binance ID
Line 40: BTC: '1GNgQc...',                      // ✏️ Your BTC address
Line 41: USDT_TRC20: 'TGcizr...',               // ✏️ Your TRC20 address
Line 42: USDT_BEP20: '0xcd76...',               // ✏️ Your BEP20 address
Line 43: LTC: 'LRgnqq...',                      // ✏️ Your LTC address
Line 44: TRX: 'TGcizr...',                      // ✏️ Your TRX address
```

### 4. ADMIN TELEGRAM ID (`database.sql`)
```sql
Line 150: VALUES (123456789, 'admin'...         // ✏️ YOUR Telegram ID here!
```

**How to find your Telegram ID:**
- Message @userinfobot on Telegram
- Copy the number it sends you

---

## 🟡 OPTIONAL EDITS (CUSTOMIZE)

### 5. COLORS & THEME (`main.css`)
```css
Line 11: --primary: #22c55e;                    // Main green color
Line 12: --secondary: #60a5fa;                  // Blue accent
Line 15: --bg-main: #0b1220;                    // Dark background
```

### 6. PLAN PRICING (`config.js`)
```javascript
Line 53: price: 10,                             // Premium plan price
Line 58: price: 25,                             // VIP plan price
```

### 7. MINIMUM DEPOSIT (`config.js`)
```javascript
Line 64: const XCOIN_TO_USDT = 1;              // Exchange rate
```

---

## 📍 WHERE FILES GO ON SERVER

```
📁 Your Website Root (public_html or www)
├── 📄 index.html or dashboard.html
├── 📄 killer.html
├── 📄 checkers.html
├── 📄 autohitters.html
├── 📄 buy.html
├── 📄 deposit.html
├── 📄 redeem.html
├── 📄 settings.html
├── 📄 admin.html              ⭐ NEW
├── 📄 config.js               ⭐ NEW (EDIT THIS)
├── 📄 main.css                ⭐ NEW (EDIT THIS)
├── 📄 db_config.php           ⭐ NEW (EDIT THIS)
│
└── 📁 api/
    ├── 📄 me.php              ⭐ NEW
    ├── 📄 buyplan.php
    ├── 📄 deposithistory.php
    └── 📁 admin/
        ├── 📄 stats.php       ⭐ NEW
        └── 📄 approve_deposit.php ⭐ NEW
```

---

## 🔧 DEPOSIT PAGE ADDRESSES (`deposit.html`)

You need to edit crypto addresses directly in `deposit.html`:

**Use Find & Replace (Ctrl+H):**

| Find This | Replace With | What It Is |
|-----------|--------------|------------|
| `753175553` | YOUR_BINANCE_ID | Binance Pay ID |
| `1GNgQcMHfAYS3XVmAFhck959vGb3T1B86t` | YOUR_BTC_ADDRESS | Bitcoin wallet |
| `TGcizrCAjTvvLCAakd1KojTVWGZEC9eEm9` | YOUR_TRC20_ADDRESS | USDT TRC20 |
| `0xcd76a1fddfc20c89b223442e9ea655d9ab3b0950` | YOUR_BEP20_ADDRESS | USDT BEP20 |
| `LRgnqqufbX2euvmiyhBU26EaMZVWMicq9A` | YOUR_LTC_ADDRESS | Litecoin |

**Each address appears 2 times** (once for display, once for copy button)

---

## ✅ VERIFICATION STEPS

After editing, verify:

1. **Database**
   - [ ] Import `database.sql` successfully
   - [ ] See 9 tables created
   - [ ] Admin user exists with YOUR Telegram ID

2. **Config Files**
   - [ ] `db_config.php` has correct credentials
   - [ ] `config.js` has your bot name and domain
   - [ ] `config.js` has your crypto addresses

3. **Deposit Page**
   - [ ] All 5 crypto addresses are YOURS
   - [ ] Copy buttons work
   - [ ] Live price updates work

4. **Admin Panel**
   - [ ] Accessible at `/admin.html`
   - [ ] Shows statistics
   - [ ] Can view users

5. **Include Files**
   - [ ] Add `<link rel="stylesheet" href="main.css">` to all HTML files
   - [ ] Add `<script src="config.js"></script>` to all HTML files

---

## 🚨 COMMON MISTAKES

❌ **Forgot to change Telegram ID in database.sql**
   → You won't be admin! Change line 150 before importing

❌ **Wrong database credentials**
   → Check `db_config.php` matches your hosting

❌ **Didn't update deposit.html addresses**
   → Payments will go to wrong wallets!

❌ **Forgot to include config.js and main.css**
   → Pages won't load properly

❌ **API files not in /api/ folder**
   → 404 errors when loading data

---

## 📝 SUMMARY: FILES TO EDIT

1. ✏️ **db_config.php** - Database credentials
2. ✏️ **config.js** - Bot name, domain, crypto addresses
3. ✏️ **database.sql** - Your Telegram ID (line 150)
4. ✏️ **deposit.html** - Crypto addresses (Find & Replace)
5. ✏️ **main.css** - (Optional) Colors and theme
6. ✏️ **All HTML files** - Add config.js and main.css includes

---

**Total time needed:** 10-15 minutes if you have all info ready!
