# Localhost Explorer

Clean and interactive localhost dashboard to explore local projects and automatically detect frameworks and correct entry points.

Localhost Explorer replaces the default Apache homepage with a modern, developer‑friendly dashboard that helps you navigate multiple local projects (PHP, JavaScript, and others) efficiently.

---

## ✨ Features

* 📁 Browse local project folders (personal, work, learning, etc.)
* 🔍 Automatic framework detection:

  * Yii2 → `/web`
  * Laravel → `/public`
  * CodeIgniter 4 → `/public`
  * Symfony → `/public`
  * CakePHP → `/webroot`
  * WordPress → root
  * Plain PHP projects
  * JavaScript projects (React, Vue, Next, Vite, etc.)
* 🚀 Smart entry point routing (public, web, dist, build)
* 🎨 Clean, modern, and responsive UI
* 🔒 Secure path traversal protection
* ⚡ Lightweight (pure PHP, no database)

---

## 🖥️ Preview

> A clean card‑based interface showing all local projects with framework badges and automatic routing.

---

## 📂 Folder Structure Example

```
/var/www/html
├── index.php   (Localhost Explorer)
├── personal/
│   └── my-yii-app/
├── work/
│   └── laravel-project/
├── learning/
│   └── react-app/
```

---

## ⚙️ Installation

### 1. Place the file

Copy `index.php` into your Apache document root:

```
/var/www/html/index.php
```

### 2. (Optional) Backup Apache default page

```bash
sudo mv /var/www/html/index.html /var/www/html/index.html.bak
```

### 3. Open in browser

```
http://localhost/
```

---

## 🧠 How It Works

Localhost Explorer scans directories and detects frameworks based on common files:

| Framework | Detection       | Entry Point         |
| --------- | --------------- | ------------------- |
| Yii2      | `yii` + `/web`  | `/web`              |
| Laravel   | `artisan`       | `/public`           |
| CI4       | `spark`         | `/public`           |
| Symfony   | `bin/console`   | `/public`           |
| CakePHP   | `bin/cake`      | `/webroot`          |
| WordPress | `wp-config.php` | `/`                 |
| JS Apps   | `package.json`  | `/dist` or `/build` |

If no framework is detected, the folder is treated as a normal directory.

---

## 🧩 Supported JavaScript Projects

* React (CRA, Vite)
* Vue (Vue CLI, Vite)
* Next.js
* Vanilla JS

> If no production build is found, the project will be marked as **JS (dev)**.

---

## 🔐 Security

* Prevents directory traversal (`../`)
* Restricts access to document root only
* Uses `realpath()` validation

---

## 🚧 Roadmap

* Auto detect running dev servers (3000, 5173, etc.)
* Open dev server links directly
* Dark mode
* Project favorites / pinning
* Docker & Python framework detection

---

## 🤝 Contributing

Contributions are welcome.

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Open a pull request

---

## 📄 License

MIT License

---

## 👨‍💻 Author

Built for developers who work with many local projects and want a clean, productive localhost experience.

---

Happy coding 🚀
