# Staging & Production Deployment Runbook (English + বাংলা)
**M&M Heritage Hotel & Suites ERP · UI/UX Renovation (`arena/01a0fe5b-mmheritagehotel`)**

---

## 1. Pre-Deploy Verification / ডিপ্লয়মেন্ট-পূর্ব যাচাইকরণ

| Step | Command | Expected Outcome (EN / বাংলা) |
|---|---|---|
| **1.1** | `npm ci && npm run ui:build` | Compiles `public/assets/custom_css/ui.css` and `public/assets/custom_css/shell.css` without diff / স্ট্যাটিক সিএসএস বিল্ড সম্পন্ন হবে |
| **1.2** | `./tools/ui-guard.sh && ./tools/ui-guard-test.sh` | 0 tripwires triggered; 39/39 self-test assertions pass / ৬টি গার্ড রেল ও ৩৯টি টেস্ট পাস করবে |
| **1.3** | `node tools/ui-check.cjs` | All 42 contract checks pass across Hotel, POS, Inventory, Account, Banquet & Website / সব কন্ট্রাক্ট চেক পাস করবে |
| **1.4** | `php tools/ui-blade-check.php` | Compiles and renders all migrated Blade views cleanly / সব ব্লেড ভিউ নির্ভুলভাবে কম্পাইল হবে |

---

## 2. GitHub Environments & Automated Deploy (`W0.1–W0.3`) / গিটহাব অটোমেটেড ডিপ্লয়মেন্ট

### English Instructions
1. In GitHub Repository **Settings → Environments**, create `staging` and `production`.
2. Add the required secrets (`STAGING_SSH_HOST`, `STAGING_SSH_USER`, `STAGING_SSH_PORT`, `STAGING_SSH_KEY`, `STAGING_DEPLOY_PATH`, `STAGING_PHP_BIN`, `STAGING_URL`) as documented in `docs/DEPLOYMENT.md`.
3. Trigger staging deployment:
   ```bash
   gh workflow run deploy-staging.yml -f ref=arena/01a0fe5b-mmheritagehotel
   ```
4. Verify on the staging server (`mmheritagehotel-test.dizihotel.com`):
   - Open browser console on any reservation invoice and confirm:
     ```js
     console.log(!document.body.innerText.includes('.col-print-1 {')) // true
     ```

### বাংলা নির্দেশিকা (Bengali Instructions)
১. গিটহাব রিপোজিটরির **Settings → Environments** থেকে `staging` এবং `production` এনভায়রনমেন্ট তৈরি করুন।
২. `docs/DEPLOYMENT.md` অনুযায়ী প্রয়োজনীয় SSH এবং সার্ভার সিক্রেট (`STAGING_SSH_HOST`, `STAGING_SSH_USER`, `STAGING_DEPLOY_PATH` ইত্যাদি) যুক্ত করুন।
৩. স্টেজিং ডিপ্লয়মেন্ট রান করুন:
   ```bash
   gh workflow run deploy-staging.yml -f ref=arena/01a0fe5b-mmheritagehotel
   ```
৪. ডিপ্লয় শেষে সার্ভারে নিচের আর্টিসান কমান্ডগুলো রান হয়েছে কি না নিশ্চিত করুন:
   ```bash
   php artisan view:clear
   php artisan cache:clear
   php artisan config:clear
   ```

---

## 3. Instant Zero-Downtime Rollback / তাৎক্ষণিক রোলব্যাক পদ্ধতি

- **English:** The entire admin shell renovation is gated by `config('ui.admin_shell')`. To revert the admin shell to the legacy Ace frame immediately without redeploying code, set `UI_ADMIN_SHELL=false` in `.env` and run `php artisan config:clear && php artisan view:clear`.
- **বাংলা:** নতুন অ্যাডমিন শেলটি `config('ui.admin_shell')` দ্বারা নিয়ন্ত্রিত। কোনো কোড পরিবর্তন ছাড়াই তাৎক্ষণিকভাবে পুরনো লেআউটে ফিরে যেতে `.env` ফাইলে `UI_ADMIN_SHELL=false` সেট করে `php artisan config:clear && php artisan view:clear` রান করুন।
