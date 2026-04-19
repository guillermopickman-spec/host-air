1 | # 🏨 Host Air Guest Manager
2 | 
3 | A modern web application for managing bookings and guest data for touristic apartments, built with Laravel and Vue.js.
4 | 
5 | ![Laravel](https://img.shields.io/badge/Laravel-10.x-orange.svg)
6 | ![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)
7 | ![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-blue.svg)
8 | ![SQLite](https://img.shields.io/badge/SQLite-Database-lightgrey.svg)
9 | 
10 | ## 🎨 Screenshots
11 | 
12 | ![Application Screenshot](screenshots/image1.PNG)
13 | ![Application Screenshot](screenshots/image2.PNG)
14 | 
15 | ## ✨ Features
16 | 
17 | - **Booking Management**: View and manage apartment bookings
18 | - **Guest Management**: Complete CRUD operations for guest data
19 | - **Modern UI**: Beautiful, responsive interface built with Vue 3 and Tailwind CSS
20 | - **Real-time Data**: Live updates through API integration
21 | - **Search & Filter**: Dynamic search functionality for easy data navigation
22 | - **Form Validation**: Client and server-side validation for data integrity
23 | 
24 | ## 🛠️ Tech Stack
25 | 
26 | ### Backend
27 | - **Laravel 10** - PHP framework
28 | - **SQLite** - Database (for development)
29 | - **Eloquent ORM** - Database abstraction
30 | - **Laravel Sanctum** - API authentication
31 | 
32 | ### Frontend
33 | - **Vue 3** - JavaScript framework
34 | - **Pinia** - State management
35 | - **Tailwind CSS** - Styling framework
36 | - **Vite** - Build tool
37 | - **Inertia.js** - SSR support
38 | 
39 | ## 📦 Installation Guide
40 | 
41 | ### Requirements
42 | - **PHP 8.2 or higher** with PDO SQLite extension enabled
43 | - **Composer** - PHP dependency manager
44 | - **Node.js 18+** - JavaScript runtime
45 | - **npm** - Node.js package manager (comes with Node.js)
46 | - **Windows 10 or higher** - Operating system
47 | 
48 | ## 🚀 Quick Start
49 | 
50 | ### Option 1: Full Automated Setup (Windows - Recommended)
51 | For the complete automated experience on Windows:
52 | 
53 | 1. Download or clone the repository
54 | 2. Double-click `full-setup.bat`
55 | 3. Wait for the setup to complete (it will open your browser automatically)
56 | 
57 | This script handles everything: dependencies, database setup, server startup, and browser launch.
58 | 
59 | ### Option 2: Manual Windows Setup
60 | If you prefer step-by-step setup:
61 | 
62 | 1. Double-click `setup.bat` to install dependencies and set up the database
63 | 2. Double-click `start-dev.bat` to start the development servers
64 | 
65 | ### Option 3: Terminal Setup (All Platforms)
66 | For manual setup or other operating systems:
67 | 
68 | 1. **Clone the repository**
69 |    ```bash
70 |    git clone <repository-url>
71 |    cd host-air
72 |    ```
73 | 
74 | 2. **Install PHP dependencies**
75 |    ```bash
76 |    composer install --ignore-platform-reqs
77 |    ```
78 | 
79 | 3. **Install JavaScript dependencies**
80 |    ```bash
81 |    npm install
82 |    ```
83 | 
84 | 4. **Environment setup**
85 |    ```bash
86 |    cp .env.example .env
87 |    php artisan key:generate
88 |    ```
89 | 
90 | 5. **Database setup**
91 |    ```bash
92 |    php artisan migrate:fresh --seed --force
93 |    npm run build
94 |    ```
95 | 
96 | 6. **Start the application**
97 |    ```bash
98 |    composer run dev
99 |    ```
100 | 
101 | 7. **Access the application**
102 |    Open your browser and navigate to `http://localhost:8000`
103 | 
104 | **Note:** This project is tested and working on Windows. The automated scripts are optimized for Windows environments.
105 | 
106 | ## 📖 Usage
107 | 
108 | ### Managing Bookings
109 | - View all bookings in a clean grid layout
110 | - Each booking card displays key information including associated guests
111 | - Click on a booking to expand and manage guest details
112 | 
113 | ### Guest Management
114 | - **Create**: Add new guests to any booking
115 | - **Read**: View guest information within booking cards
116 | - **Update**: Edit existing guest details
117 | - **Delete**: Remove guests from bookings
118 | 
119 | ### Search Functionality
120 | - Use the search bar to filter bookings by guest name
121 | - Real-time filtering as you type
122 | - Clear search to view all bookings
123 | 
124 | ## 🏗️ Project Structure
125 | 
126 | ```
127 | host-air/
128 | ├── app/
129 | │   ├── Http/Controllers/     # API controllers
130 | │   └── Models/               # Eloquent models
131 | ├── database/
132 | │   ├── migrations/           # Database schema
133 | │   ├── factories/            # Model factories
134 | │   └── seeds/               # Database seeders
135 | ├── resources/
136 | │   ├── js/                  # Vue.js frontend
137 | │   │   ├── components/      # Vue components
138 | │   │   ├── pages/          # Page components
139 | │   │   └── stores/         # Pinia stores
140 | │   └── views/              # Blade templates
141 | ├── routes/
142 | │   ├── api.php            # API routes
143 | │   └── web.php            # Web routes
144 | └── tests/                 # Test files
145 | ```
146 | 
147 | ## 🧪 Testing
148 | 
149 | ### Unit Tests
150 | ```bash
151 | php artisan test
152 | ```
153 | 
154 | ### Feature Tests
155 | ```bash
156 | php artisan test --testsuite=Feature
157 | ```
158 | 
159 | ### Browser Tests
160 | ```bash
161 | php artisan test --testsuite=Browser
162 | ```
163 | 
164 | ## 🔧 Development
165 | 
166 | ### Running Migrations
167 | ```bash
168 | php artisan migrate
169 | ```
170 | 
171 | ### Seeding Database
172 | ```bash
173 | php artisan db:seed
174 | ```
175 | 
176 | ### Quick Setup Script
177 | For a complete setup including database seeding with test data:
178 | ```bash
179 | # Windows
180 | setup.bat
181 | 
182 | # Or manually:
183 | composer install
183 | npm install
184 | php artisan key:generate
185 | php artisan migrate
186 | php artisan db:seed
187 | npm run build
188 | php artisan serve
189 | ```
190 | 
191 | The seeding process will populate your database with:
192 | - 1 test user
193 | - 100 sample bookings with various check-in/check-out dates and statuses
194 | - 10 sample guests with complete contact information
195 | 
196 | ### Generate Dummy Data
197 | ```bash
198 | php artisan initialize-bookings
199 | ```
200 | 
201 | ### Frontend Development
202 | ```bash
203 | npm run dev    # Development server
204 | npm run build  # Production build
205 | npm run lint   # Linting
206 | ```
207 | 
208 | ## 📋 API Endpoints
209 | 
210 | ### Bookings
211 | - `GET /api/bookings` - Get all bookings with guests
212 | - `POST /api/bookings` - Create new booking
213 | - `PUT /api/bookings/{id}` - Update booking
214 | - `DELETE /api/bookings/{id}` - Delete booking
215 | 
216 | ### Guests
217 | - `GET /api/guests` - Get all guests
218 | - `POST /api/guests` - Create new guest
219 | - `PUT /api/guests/{id}` - Update guest
220 | - `DELETE /api/guests/{id}` - Delete guest
221 | 
222 | ## 🤝 Contributing
223 | 
224 | 1. Fork the repository
225 | 2. Create your feature branch (`git checkout -b feature/amazing-feature`)
226 | 3. Commit your changes (`git commit -m 'Add amazing feature'`)
227 | 4. Push to the branch (`git push origin feature/amazing-feature`)
228 | 5. Open a Pull Request
229 | 
230 | ## 📄 License
231 | 
232 | This project is open source and available under the [MIT License](LICENSE).
233 | 
234 | ## 🙏 Acknowledgments
235 | 
236 | - Built with [Laravel](https://laravel.com)
237 | - Frontend powered by [Vue.js](https://vuejs.org)
238 | - Styled with [Tailwind CSS](https://tailwindcss.com)
239 | - Database management with [SQLite](https://sqlite.org)
240 | 
241 | ---
242 | 
243 | **Made with ❤️ for managing touristic apartment bookings**
