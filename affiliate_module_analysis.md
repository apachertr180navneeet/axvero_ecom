# E-Commerce Affiliate Module Gap Analysis

This document provides a detailed comparison between your **Affiliate Marketing Module Requirements** and the current state of the codebase.

---

## 1. Feature Status Summary

| Section / Feature | Requirement | Current Codebase Status | Status |
| :--- | :--- | :--- | :--- |
| **Frontend Dashboard** | Display: Total Referrals, Orders, Sales, Earned, Pending, Approved, Withdrawn, and Wallet Balance. | Only displays: Clicks, Sold Items, Delivered Items, and Total Earnings. Missing Pending/Approved breakdowns, Total Referrals, and Wallet Balance cards. | **Partially Completed** |
| **Referral Management** | Generate referral code/URL, copy link functionality, optional QR code. | Referral code & URL generation exists, copy-to-clipboard button exists. **QR Code is missing**. | **Completed (Optional QR Missing)** |
| **Customer List** | Display Customer Name, Registration Date, Total Orders, Purchase Amount, and Status. | No customer tracking views or database relationships exist for referred customer lists. | **Missing** |
| **Commission History** | Display Order #, Customer Name, Order/Commission Amount, %, Status, and Date. | A basic table of logs exists displaying Order, Amount, Log Type (Click vs. Sale), and Date. Missing Customer Name, Commission %, and Status filter details. | **Partially Completed** |
| **Wallet Section** | Display Total Earnings, Available/Pending Balance, and Withdrawal History. | Withdrawal history and payment history views exist. **Dedicated wallet summary cards (Available/Pending Balance) are missing**. | **Partially Completed** |
| **Withdrawal Request** | Submit withdraw request with amount, payment method, bank/UPI details, and validation checks. | A form exists to request withdrawal by entering an amount. No selection of payment methods or UPI details on request. **No validation checks for limits or balance**. | **Partially Completed** |
| **Admin Panel: Affiliate Management** | View affiliate list, approve/reject accounts, activate/deactivate, and view stats/earnings. | Admins can view the list, toggle approval/activation, and inspect withdrawal requests. Detailed profile stats sheets are missing. | **Mostly Completed** |
| **Admin Panel: Commission Settings** | Configure Global (Fixed/%), Product-wise, Category-wise, and Affiliate-wise rates. Priority hierarchy logic. | **Completely Missing**. Admin can only toggle 3 basic options: product sharing, category-wise, first purchase. No rates can be configured. | **Missing** |
| **Admin Panel: Withdrawal Settings** | Configure min/max limits, frequency limits, and allowed payment methods. | No admin options or configuration fields exist for withdrawal limits, frequency, or methods. | **Missing** |
| **Admin Panel: Payout Management** | Approve/reject requests, mark as paid, add transaction reference, add remarks. | Admins can approve and reject requests. The payment action spawns a payout entry. **No fields exist for transaction reference and remarks**. | **Partially Completed** |
| **Commission Logic** | Store cookie; associate customer on signup; pend commission; approve on delivery & return period expiry; reject on order cancellation. | **Entirely Missing / Inactive**. In the active `app/` files, no cookie is saved, registration is not linked, and order checkout doesn't capture the referrer. (The shadow `bootstrap/app/` folder has basic cookie tracking, but lacks return period or status flow logic). | **Missing** |

---

## 2. Database Tables Comparison

Here is a comparison of your requested database schema vs. what is currently implemented in your database migrations:

| Requested Table & Columns | Current Migration Table & Columns | Gap Analysis |
| :--- | :--- | :--- |
| **`affiliates`**<br>- `id`<br>- `user_id`<br>- `referral_code`<br>- `status`<br>- `created_at`<br>- `updated_at` | **`affiliate_users`**<br>- `id`<br>- `user_id`<br>- `status`<br>- `created_at`<br>- `updated_at` | The table name is `affiliate_users`. The `referral_code` column is missing from this table (it is currently saved directly in the `users` table instead). |
| **`affiliate_customers`**<br>- `id`<br>- `affiliate_id`<br>- `customer_id`<br>- `created_at` | *Does not exist* | **Entirely missing**. There is no database structure tracking which customer was referred by which affiliate. |
| **`affiliate_commissions`**<br>- `id`<br>- `affiliate_id`<br>- `customer_id`<br>- `order_id`<br>- `commission_type`<br>- `commission_value`<br>- `commission_amount`<br>- `status`<br>- `approved_at`<br>- `created_at` | **`affiliate_logs`**<br>- `id`<br>- `user_id` (Affiliate ID)<br>- `order_id`<br>- `order_detail_id`<br>- `referral_amount`<br>- `log_type` (Click vs. Sale)<br>- `created_at`<br>- `updated_at` | **Significantly different**. The current `affiliate_logs` table lacks `customer_id`, `commission_type`, `commission_value`, `status`, and `approved_at`. There is no way to mark commissions as pending/approved/rejected. |
| **`affiliate_wallets`**<br>- `id`<br>- `affiliate_id`<br>- `total_earned`<br>- `total_withdrawn`<br>- `available_balance`<br>- `updated_at` | *Does not exist* | **Missing**. Balance is currently aggregated on the fly using stats or earnings logs instead of maintaining a wallet ledger. |
| **`affiliate_withdraw_requests`**<br>- `id`<br>- `affiliate_id`<br>- `amount`<br>- `payment_method`<br>- `account_details`<br>- `transaction_id`<br>- `remarks`<br>- `status`<br>- `approved_by`<br>- `approved_at`<br>- `created_at` | **`affiliate_withdraw_requests`**<br>- `id`<br>- `user_id`<br>- `amount`<br>- `status`<br>- `created_at`<br>- `updated_at` | **Missing columns**. The current table lacks `payment_method`, `account_details`, `transaction_id`, `remarks`, `approved_by`, and `approved_at`. |
| **`affiliate_settings`**<br>- `id`<br>- `default_commission_type`<br>- `default_commission_value`<br>- `minimum_withdrawal_amount`<br>- `maximum_withdrawal_amount`<br>- `withdrawal_frequency`<br>- `allowed_payment_methods`<br>- `updated_at` | **`affiliate_configs`** & **`affiliate_options`**<br>- `id`<br>- `type`<br>- `value` / `details` / `status` | Configured as generic key-value tables. None of the columns like `default_commission_type`, `minimum_withdrawal_amount`, `withdrawal_frequency`, or `allowed_payment_methods` are implemented or stored in keys. |

---

## 3. Key Missing Backend Logic Blocks

To make the module function according to your specifications, the following logic needs to be developed/restored:

1. **Tracking Hooks (Cookie + Cart + Checkout)**:
   * Middleware/Controller hooks to capture `?ref=...` and queue the `referral_code` cookie.
   * Modifying checkout logic to assign `referred_by` to the customer on registration.
   * Updating cart data and order details to record the referrer’s code during purchase.
2. **Commission Calculation Engine**:
   * Implementing the priority hierarchy logic:
     $$\text{Affiliate Custom Rate} \rightarrow \text{Product Custom Rate} \rightarrow \text{Category Rate} \rightarrow \text{Global Rate}$$
   * Storing the calculated commission in a new commission table status `Pending`.
3. **Order Lifecycle Toggles**:
   * Listening to order status transitions:
     * When delivered and return period expires $\rightarrow$ Status changes to `Approved` and available balance increases.
     * When cancelled or refunded $\rightarrow$ Status changes to `Rejected`.
4. **Withdrawal Validation**:
   * Checking limits (minimum/maximum) and available wallet balance before allowing withdrawals.
   * Allowing admins to add remarks and transaction IDs on payout approval.
