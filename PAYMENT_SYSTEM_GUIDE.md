# Dynamic Payment System - Quick Start Guide

## 🚀 Setup Instructions

### Step 1: Run Database Migration

1. Open your browser and navigate to:
   ```
   http://localhost/crm/database/run_payment_migration.php
   ```

2. This will:
   - Create `payment_methods` table
   - Create `payment_logs` table
   - Update `student_transactions` table
   - Insert default payment methods including **Test Payment**

3. You should see success messages for all steps

### Step 2: Test the Payment Flow

1. **Navigate to Wallet Page**:
   ```
   http://localhost/crm/parent/student_wallet.php
   ```

2. **You'll see**:
   - Amount selection (₹100, ₹500, ₹1000 or custom)
   - Payment method cards
   - **"Test Payment (Demo)"** should be visible and active

3. **Make a Test Payment**:
   - Select an amount (e.g., ₹500)
   - Click on "Test Payment (Demo)" card
   - Click "Proceed to Payment"
   - Payment will be approved instantly!
   - Wallet balance will update immediately
   - Bonus will be applied automatically

### Step 3: Configure Real Payment Methods (Optional)

1. **Go to Admin Panel**:
   ```
   http://localhost/crm/admin/payment_methods.php
   ```

2. **You'll see all payment methods**:
   - ✅ Test Payment (Active)
   - ❌ Razorpay (Inactive)
   - ❌ UPI/QR Code (Inactive)
   - ❌ Bank Transfer (Inactive)

3. **To activate Razorpay**:
   - Click Edit button on Razorpay
   - Add your Razorpay credentials
   - Toggle Active status
   - Save

## 📋 Features

### Test Payment Mode
- ✅ **No credentials required**
- ✅ **Instant approval**
- ✅ **Perfect for testing**
- ✅ **Automatic bonus calculation**
- ✅ **Real wallet updates**

### Supported Payment Methods

| Method | Status | Description |
|--------|--------|-------------|
| **Test Payment** | ✅ Active | Instant approval for testing |
| **Razorpay** | ⚙️ Configure | Cards, UPI, Wallets, Net Banking |
| **UPI/QR Code** | ⚙️ Configure | Scan & pay, manual verification |
| **Bank Transfer** | ⚙️ Configure | NEFT/IMPS, manual verification |

## 🎯 How It Works

### For Users (Students/Parents/Counselors)

1. Select recharge amount
2. Choose payment method from available options
3. Complete payment based on method type:
   - **Test**: Instant approval
   - **Razorpay**: Opens Razorpay checkout
   - **UPI/QR**: Shows QR code, upload proof
   - **Manual**: Shows bank details, upload proof

### For Admins

1. Go to **Admin → Payment Methods**
2. Add/Edit/Enable/Disable payment methods
3. Configure credentials for each gateway
4. Upload QR codes for UPI payments
5. Approve manual payments (coming soon)

## 🔧 Configuration Examples

### Razorpay Configuration
```json
{
  "key_id": "rzp_test_YOUR_KEY_ID",
  "key_secret": "YOUR_KEY_SECRET",
  "mode": "test"
}
```

### UPI/QR Code Configuration
```json
{
  "upi_id": "merchant@upi",
  "account_name": "Student CRM",
  "auto_approve": false
}
```

### Bank Transfer Configuration
```json
{
  "account_number": "1234567890",
  "ifsc": "BANK0001234",
  "bank_name": "Bank Name",
  "account_holder": "Account Holder Name",
  "auto_approve": false
}
```

## ✅ Testing Checklist

- [ ] Run database migration successfully
- [ ] See Test Payment method on wallet page
- [ ] Select amount (₹100, ₹500, or custom)
- [ ] Click Test Payment method
- [ ] Click "Proceed to Payment"
- [ ] See success message with bonus details
- [ ] Verify wallet balance updated
- [ ] Check transaction in History tab
- [ ] Verify transaction in database

## 🎁 Bonus Structure

| Recharge Amount | Base Bonus | Premium +10% | Gold +20% |
|----------------|------------|--------------|-----------|
| ₹500 | ₹25 | ₹75 | ₹125 |
| ₹1000 | ₹60 | ₹160 | ₹260 |
| ₹2000 | ₹150 | ₹350 | ₹550 |
| ₹5000 | ₹400 | ₹900 | ₹1400 |

## 🔒 Security Notes

- Test payment is for development/testing only
- Disable test payment in production
- Store real credentials securely
- Use HTTPS in production
- Validate all payment proofs manually

## 📞 Support

If you encounter any issues:
1. Check browser console for errors
2. Verify database migration completed
3. Check PHP error logs
4. Ensure all files are uploaded correctly

## 🎉 Next Steps

1. ✅ Test the payment flow with Test Payment
2. Configure Razorpay with your credentials
3. Add UPI QR code for manual payments
4. Test with real small amounts
5. Disable Test Payment before going live
