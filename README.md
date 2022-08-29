# Unbelievable Digital Booking System Plugin

This is a wordpress plugin created to help you keep track of all your bookings in your hotel, motel, car rental, etc. It also provides Elementor widgets for you to allow your site visitors to book some of your products. All the products you add using this plugin will be added to your woocommerce products list automaticlly so they will have the same functionality as a noraml woocommerce product. However, you won't be able to edit them in the woocommerce products page. 

For this version of the plugin, "room" and "booking" exist as products. A "room" include the attributes "price", "maximum number of visitors", "minimum number of visitors", and "amenties". A "booking" is more complicated and contains inforamtion of all the rooms that were booked in one booking and the booker information.
The "booking" information contains: "rooms", "status", "billing details", "price", "payment method", "payment paid", "date", and the woocommerce order id.

## Elementor Widgets

### Products List
__Widget name__: UNB Products List

This widget will allow you to display a list all your products for your visitors to choose from. However, this widget won't display any information about the product except its title. You should use the specific list widget for each product (eg. rooms list). You can turn on and off the products you want to display in the widget settings.

### Rooms List
__Widget name__: UNB Rooms List

This widget will only display the rooms you have added with all their details such as price, min booking days, max number of visitors, amenities, and any thumbnail. You can change the maximum number of rooms in the list in the widget settings (min: 1, max: 100).

### Book Room Button
__Widget name__: UNB Book Room Button

This widget should only be included in a rooms page. It will display 4 input fields, the check in and check out dates, the number of visitors, and a button to book the room. A user won't be able to book a room (add it to cart)
1) If the room is already booked
2) If the chosen booking days is less than this room's min booking days
3) If the chosen number of visitos is more than this room's max number of visitors

## Settings

### Products(Rooms) settings

You can pick the default values of your products (for now just "room") in the admin settings page.

### Currency settings

You can pick the default currency of your website in the plugin general page.
