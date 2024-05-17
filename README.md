<p float="left">
  <img src="./logos/logo-inserm.png" alt="Inserm" height="60" hspace="10"/>
  <img src="./logos/logo_ibrain.png" alt="iBrain" height="60" hspace="10"/>
  <img src="./logos/logo-univ.png" alt="Univ Tours" height="60" hspace="10"/>
</p>

# Equipment Reservation

Equipment Reservation is a web application aimed at simplifying the process of reserving equipment for various purposes. Developed using Laravel for the backend and React for the frontend, it offers users an intuitive interface to book and manage their reservations with ease.

## Table of Contents

- [Introduction](#introduction)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [Features](#features)
- [Tests](#tests)
- [Technologies Used](#technologies-used)
- [Author](#author)

## Introduction

This application was developed as part of my end-of-study internship in computer science. It was created at the request of the iBrain laboratory of Inserm and the University of Tours, which needed a system for equipment reservation.

The application features an authentication system to identify users. It allows users to schedule manipulations, which are events comprising a list of required equipment, the location, the team accompanying the person performing the manipulation, and the start and end date and time. Users can also utilize a recurrence system to schedule daily or weekly manipulations. Additionally, the application enables users to define preferences to facilitate the creation of recurring manipulations.

The application includes an admin role, which enables management of the equipment and locations list, as well as all manipulations. The admin role also handles user management tasks such as validating registrations, modifying user roles, or deleting accounts.

## Prerequisites

- PHP
- Composer
- npm
- MySQL

## Installation

Follow these steps to install the application locally:

1. Clone the repository from GitHub:

```bash
git clone https://github.com/Jepifa/equipment-reservation.git
```

2. Go to the repo folder:

```bash
cd equipment-reservation
```

3. Install PHP dependencies:

```bash
cd backend
composer install
```

4. Copy the environment file:

```bash
cp .env.example .env
```

> Edit .env file with your environment variables (db*database and MAIL*\* if you want to use email password reset link)

5. Generate the app key:

```bash
php artisan key:generate
```

6. Migrate and seed the db:

```bash
php artisan migrate --seed
```

> Seeding is required to establish roles and create an admin account.

7. Install frontend dependencies:

```bash
cd ../frontend
npm install
```

## Usage

Once installed, follow these steps to run the application locally:

1. Launch the React app:

```bash
cd frontend
npm run dev
```

2. Launch the Laravel app:

```bash
cd backend
php artisan serve
```

3. Visit localhost:5173 in your web browser.

4. Log in using the provided admin or user credentials.

- Login as an admin

  - Email : `admin@example.com`
  - Password : `password`

- Login as a user
  - Email : `user@example.com`
  - Password : `password`

## Features

- User authentication and authorization system
- Schedule equipment, location, and team for specific dates and times to plan a manipulation
- Use recurrence to schedule multiple manipulations
- Set up a preference for a manipulation, specifying equipment, location, and team
- View and manage existing reservations
- Admin panel for managing user accounts, equipment, locations and manipulations

## Test

To launch feature tests of the backend:

```bash
cd backend
php artisan test --testsuite=Feature
```

## Technologies Used

### Backend

- **Laravel 10**
  - Composer
  - Breeze API
  - Sanctum
  - Spatie (laravel-permissions)
  - Telescope
  - Pest
- **MySQL**

### Frontend

- **React**
  - Vite.js
  - React Router
  - Tailwind CSS
  - Material-UI (MUI)
  - Redux Toolkit
  - RTK Query
  - Formik
  - Yup
  - Dayjs

## Author

- Jean-Pierre Faucon
