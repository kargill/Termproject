# ReMarket - PHP MySQL Second-Hand Marketplace

## Setup
1. Extract this folder into `C:\xampp\htdocs\marketplace`
2. Start **Apache** and **MySQL** in XAMPP
3. Open **phpMyAdmin**
4. Import `database.sql`
5. Visit `http://localhost/marketplace/`

## Demo accounts
- Admin: `admin@example.com` / `password`
- User: `alice@example.com` / `password`
- User: `bob@example.com` / `password`

## Main features
- User registration and login
- Product browsing and filtering
- Product detail page
- Sell item with image upload
- Cart and checkout
- Order history
- Admin dashboard
- Admin edit and delete product

## Database name
`marketplace`

## Notes
- Uploaded images go to `assets/uploads/`
- The project uses PDO and prepared statements
- Passwords are stored securely with `password_hash`
