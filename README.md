# CP3407 Advanced Software Engineering

## Smart Campus Food Ordering System

The Smart Campus Food Ordering System is a web application that allows students to browse food from different campus stores, create an account, add food to a shopping cart, and place orders.

Campus food stall staff will later be able to manage menu items and update order statuses.

## Technology Stack

* HTML
* CSS
* JavaScript
* PHP
* MySQL
* Docker
* Docker Compose
* phpMyAdmin
* GitHub

## Current Features

* User registration
* User login
* Campus store menu
* Menu data loaded from MySQL
* Shopping cart
* Checkout system
* Orders stored in MySQL
* Docker development environment

# Iteration 1 Project Board

## TODO

* [ ] Develop order tracking page
* [ ] Add logout feature
* [ ] Test all completed features
* [ ] Create system design documentation

## IN PROGRESS

* [ ] Improve project README and GitHub documentation

## DONE

* [x] Create user registration page
* [x] Connect registration to MySQL
* [x] Create user login page
* [x] Connect login to MySQL
* [x] Create campus food menu
* [x] Load stores and menu items from MySQL
* [x] Add shopping cart
* [x] Save checkout orders in MySQL
* [x] Configure Docker development environment
* [x] Add phpMyAdmin database management

# User Stories

## User Login

Users can log into their account to access and manage their orders.

**Priority:** 10
**Estimated effort:** 2 days
**Status:** Done

## Register Account

New users can create an account so they can place food orders.

**Priority:** 10
**Estimated effort:** 2 days
**Status:** Done

## Browse Menu

Students can browse food items from different campus stores.

**Priority:** 10
**Estimated effort:** 3 days
**Status:** Done

## Shopping Cart

Users can add multiple food items to their cart before placing an order.

**Priority:** 20
**Estimated effort:** 3 days
**Status:** Done

## Order Tracking

Users can view the progress and current status of their food orders.

**Priority:** 20
**Estimated effort:** 2 days
**Status:** Todo

## Menu Management

Staff can add, edit, remove, and update the availability of menu items.

**Priority:** 20
**Estimated effort:** 3 days
**Status:** Todo

## Payment Option

Users can pay online for faster checkout.

**Priority:** 30
**Estimated effort:** 4 days
**Status:** Todo

## Notifications

Users receive a notification when their order status changes or their food is ready.

**Priority:** 30
**Estimated effort:** 2 days
**Status:** Todo

## Profile Settings

Users can update their account information.

**Priority:** 40
**Estimated effort:** 1 day
**Status:** Todo

## Dark Mode

Users can switch between light and dark display modes.

**Priority:** 50
**Estimated effort:** 1 day
**Status:** Todo

# Priority Guide

* **10:** Essential core feature
* **20:** Important feature
* **30:** Useful enhancement
* **40:** Optional feature
* **50:** Low-priority extra feature

# Database Tables

The current MySQL database contains:

* `users`
* `stores`
* `menu_items`
* `orders`
* `order_items`

# Running the Project with Docker

Create a `.env` file based on `.env.example`, then run:

```bash
docker compose up -d --build
```

Open the website:

```text
http://localhost:8082
```

Open phpMyAdmin:

```text
http://localhost:8083
```

Stop the project:

```bash
docker compose down
```

# Security

Database passwords are stored in a local `.env` file.

The `.env` file is excluded from GitHub using `.gitignore`. Only `.env.example` should be committed.

User passwords are hashed before being stored in MySQL.

# Next Development Step

The next user story is **Order Tracking**. Logged-in users will be able to view their previous orders, total prices, dates, and current order statuses.
