# 🚀 COMPLETE SETUP GUIDE - CyborX Bot Dashboard

## 📋 TABLE OF CONTENTS
1. [File Structure](#file-structure)
2. [Installation Steps](#installation-steps)
3. [Configuration Guide](#configuration-guide)
4. [Database Setup](#database-setup)
5. [API Setup](#api-setup)
6. [Admin Panel Access](#admin-panel-access)
7. [Customization](#customization)

---

## 📁 FILE STRUCTURE

```
cyborx-bot/
├── Frontend (HTML Pages)
│   ├── dashboard.html      # Main user dashboard
│   ├── killer.html         # CC Killer tool
│   ├── checkers.html       # CC Checker tool
│   ├── autohitters.html    # Auto Hitter tool
│   ├── buy.html           # Buy plans page
│   ├── deposit.html       # Deposit page
│   ├── redeem.html        # Redeem codes page
│   ├── settings.html      # User settings
│   └── admin.html         # Admin panel ⭐ NEW
│
├── Configuration Files
│   ├── config.js          # Main config ⭐ EDIT THIS
│   ├── main.css           # Styles ⭐ EDIT THIS
│   └── db_config.php      # Database connection ⭐ EDIT THIS
│
├── Database
│   └── database.sql       # Database schema ⭐ RUN THIS
│
└── Backend API (Create these)
    └── api/
        ├── me.php
        ├── buyplan.php
        ├── deposithistory.php
        └── admin/
            ├── stats.php
            └── approve_deposit.php
```

---

## ⚙️ INSTALLATION STEPS

### STEP 1: Upload Files
1. Upload all HTML files to your web server
2. Upload `config.js` and `main.css` to same directory
3. Upload `db_config.php` to root or `/api/` folder

### STEP 2: Database Setup
1. Open phpMyAdmin or MySQL console
2. Import `database.sql` file
3. This creates all necessary tables

```sql
mysql -u username -p database_name < database.sql
```

### STEP 3: Configure Database Connection
Edit `db_config.php`:

```php
define('DB_HOST', 'localhost');     // Your DB host
define('DB_NAME', 'cyborx_bot');    // Your DB name
define('DB_USER', 'your_username'); // Your DB username
define('DB_PASS', 'your_password'); // Your DB password
```

### STEP 4: Update Configuration
Edit `config.js`:

```javascript
const CONFIG = {
  BOT_NAME: 'YourBotName',          // Change this
  BOT_DOMAIN: 'yourdomain.com',     // Change this
};

const CRYPTO_WALLETS = {
  BINANCE_ID: 'your_binance_id',    // Change this
  BTC: 'your_btc_address',          // Change this
  USDT_TRC20: 'your_trc20_address', // Change this
  USDT_BEP20: 'your_bep20_address', // Change this
  LTC: 'your_ltc_address',          // Change this
};
```

### STEP 5: Add Config to HTML Files
Add these lines to `<head>` section of ALL HTML files:

```html
<link rel="stylesheet" href="main.css">
<script src="config.js"></script>
```

---

## 🔧 CONFIGURATION GUIDE

### 1. Change Bot Name
**In `config.js`:**
```javascript
BOT_NAME: 'YourBotName'
```

**In ALL HTML files:**
Find and replace:
- `<title>CyborX</title>` → `<title>YourBotName</title>`
- `<div>CyborX</div>` → `<div>YourBotName</div>`

### 2. Change Crypto Addresses
**ONLY edit `deposit.html`:**

Find these lines and replace addresses:
```html
<!-- Line ~150: BINANCE ID -->
<div class="text-xs text-slate-400 truncate">753175553</div>
<button data-copy="753175553">Copy</button>

<!-- Line ~160: BTC -->
<div class="text-xs text-slate-400 truncate">1GNgQc...</div>
<button data-copy="1GNgQc...">Copy</button>

<!-- Continue for all crypto types -->
```

**Quick Method:**
Use Find & Replace (Ctrl+H) in `deposit.html`:
1. Find: `753175553` → Replace: `YOUR_BINANCE_ID`
2. Find: `1GNgQcMHfAYS3XVmAFhck959vGb3T1B86t` → Replace: `YOUR_BTC`
3. Find: `TGcizrCAjTvvLCAakd1KojTVWGZEC9eEm9` → Replace: `YOUR_TRC20`
4. Find: `0xcd76a1fddfc20c89b223442e9ea655d9ab3b0950` → Replace: `YOUR_BEP20`
5. Find: `LRgnqqufbX2euvmiyhBU26EaMZVWMicq9A` → Replace: `YOUR_LTC`

### 3. Change Colors/Theme
**Edit `main.css`:**
```css
:root {
  --primary: #22c55e;      /* Main color */
  --secondary: #60a5fa;    /* Secondary color */
  --bg-main: #0b1220;      /* Background */
}
```

---

## 💾 DATABASE SETUP

### Tables Created:
1. **users** - User accounts and balances
2. **deposits** - Deposit requests
3. **transactions** - All transactions history
4. **redeem_codes** - Redeem codes
5. **redeem_history** - Code usage history
6. **proxies** - User proxies
7. **checker_history** - Checker logs
8. **settings** - Bot settings
9. **admin_logs** - Admin activity logs

### Create First Admin:
Edit `database.sql` line 150 BEFORE importing:

```sql
INSERT INTO users (telegram_id, username, first_name, is_admin, balance)
VALUES (123456789, 'admin', 'Admin', TRUE, 0.00)
```

**Change `123456789` to YOUR Telegram ID!**

To find your Telegram ID:
- Message @userinfobot on Telegram
- It will reply with your ID

---

## 🔌 API SETUP

You need to create backend PHP files in `/api/` folder:

### Required API Files:

**1. `/api/me.php`** - Get user info
```php
<?php
require_once '../db_config.php';

$telegram_id = $_POST['telegram_id'] ?? null;
$user = getUserByTelegramId($telegram_id);

echo json_encode([
    'success' => true,
    'user' => $user
]);
?>
```

**2. `/api/buyplan.php`** - Purchase plans
**3. `/api/deposithistory.php`** - Get deposits
**4. `/api/admin/stats.php`** - Admin statistics
**5. `/api/admin/approve_deposit.php`** - Approve deposits

(Full API code available on request)

---

## 👑 ADMIN PANEL ACCESS

### Access Admin Panel:
1. Go to `yourdomain.com/admin.html`
2. Features:
   - ✅ View all users
   - ✅ Approve/reject deposits
   - ✅ Create redeem codes
   - ✅ Manage user balances
   - ✅ View statistics
   - ✅ Bot settings

### Security:
Add authentication check in `admin.html`:

```javascript
// Add this at top of <script> section
const ADMIN_TELEGRAM_ID = 123456789; // Your Telegram ID

// Check if user is admin
if (userTelegramId !== ADMIN_TELEGRAM_ID) {
  window.location.href = 'dashboard.html';
}
```

---

## 🎨 CUSTOMIZATION

### Change Logo:
Replace this in ALL HTML files:
```html
<i class="fas fa-robot text-green-400"></i>
<span>CyborX</span>
```

With your custom logo/icon.

### Add Custom Pages:
1. Copy any existing HTML file
2. Update content
3. Add navigation link in sidebar
4. Update `config.js` if needed

### Modify Plan Prices:
Edit `config.js`:
```javascript
const PLANS = {
  PREMIUM: {
    name: 'Premium',
    price: 10,  // Change price here
  }
}
```

---

## ✅ TESTING CHECKLIST

After setup, test these:

- [ ] Database connection works
- [ ] User can register (creates row in `users` table)
- [ ] Deposit page shows YOUR crypto addresses
- [ ] Copy buttons work for addresses
- [ ] Admin panel loads at `/admin.html`
- [ ] Admin can see pending deposits
- [ ] Redeem code creation works
- [ ] Colors/branding updated everywhere

---

## 🆘 TROUBLESHOOTING

### "Database connection failed"
- Check `db_config.php` credentials
- Verify database exists
- Check MySQL is running

### "API not found"
- Create missing API files in `/api/` folder
- Check file permissions (755)
- Verify paths in `config.js`

### Admin panel blank
- Open browser console (F12)
- Check for JavaScript errors
- Verify `config.js` is loaded

### Crypto addresses not updating
- Clear browser cache
- Check you edited `deposit.html`
- Verify both display div AND data-copy attribute

---

## 📞 SUPPORT

For API code or additional help:
1. Review this guide carefully
2. Check browser console for errors
3. Test database connection first
4. Verify all files uploaded correctly

---

## 🎯 QUICK START SUMMARY

1. ✅ Import `database.sql`
2. ✅ Edit `db_config.php` (database credentials)
3. ✅ Edit `config.js` (bot name, domain, crypto addresses)
4. ✅ Update crypto addresses in `deposit.html`
5. ✅ Add your Telegram ID as admin in database
6. ✅ Upload all files to server
7. ✅ Test: Open dashboard, deposit page, admin panel
8. ✅ Done! 🚀

---

**Version:** 1.0  
**Last Updated:** November 2025
