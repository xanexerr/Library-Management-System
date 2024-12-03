# Library-Management-System

 - Student from Information Technology, Hatyai Technical College.

<img src="https://user-images.githubusercontent.com/25181517/192158954-f88b5814-d510-4564-b285-dff7d6400dad.png" alt="" width="100"/>,
<img src="https://user-images.githubusercontent.com/25181517/183898674-75a4a1b1-f960-4ea9-abcb-637170a00a75.png" alt="" width="100"/>,
<img src="https://user-images.githubusercontent.com/25181517/183898054-b3d693d4-dafb-4808-a509-bab54cf5de34.png" alt="" width="100"/>
<img src="https://github-production-user-asset-6210df.s3.amazonaws.com/76662862/283243401-dbbc299a-8356-45e4-9d2e-a6c21b4569cf.png?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAVCODYLSA53PQK4ZA%2F20240822%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20240822T171411Z&X-Amz-Expires=300&X-Amz-Signature=36ff0f927acd0aa74d0025ba74e2d2b3c2f8e64e7857f5b23275ca2bff6ef057&X-Amz-SignedHeaders=host&actor_id=124129619&key_id=0&repo_id=364793759" alt="" width="100"/>,
<img src="https://user-images.githubusercontent.com/25181517/117447155-6a868a00-af3d-11eb-9cfe-245df15c9f3f.png" alt="" width="100"/>,
<img src="https://user-images.githubusercontent.com/25181517/183896128-ec99105a-ec1a-4d85-b08b-1aa1620b2046.png" alt="" width="100"/>,


 
## Handbook and More

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Features](#features)
- [Contributing](#contributing)
- [License](#license)

## Installation

### Prerequisites

Before you begin, ensure you have the following software installed:

- [XAMPP](https://www.apachefriends.org/index.html) (Apache, MySQL, PHP)
- A web browser (e.g., Chrome, Firefox)

### Steps

1. **Download and Install XAMPP**:
    - Download the latest version of XAMPP for your operating system from the [official website](https://www.apachefriends.org/download.html).
    - Run the installer and follow the instructions to install XAMPP.

2. **Clone the Repository**:
    - Clone this repository to your local machine using the command:
      ```bash
      git clone https://github.com/xanexerr/Library-Management-System.git
      ```
    - Alternatively, you can download the ZIP file from GitHub and extract it.

3. **Move Project to XAMPP’s `htdocs` Folder**:
    - After cloning or extracting the project, move the project folder to the `htdocs` directory inside your XAMPP installation folder. Typically, the path looks like this:
      ```bash
      C:\xampp\htdocs\
      ```
    - Rename the folder to your desired project name if necessary.

4. **Start XAMPP**:
    - Open the XAMPP Control Panel and start the `Apache` and `MySQL` modules.

5. **Set Up Database**:
    - Open a web browser and go to `http://localhost/phpmyadmin/`.
    - Create a new database (e.g., `your_database_name`).
    - Import the database schema from the `database/sample_db.sql` file located in the project directory.

## Configuration

1. **Database Configuration**:
    - Open the project’s configuration file (e.g., `connection.php`) located in the root directory.
    - Set your database credentials:
      ```php
        $server = "localhost";
        $username = "root";
        $password = "";
        $database = "your_database";
      ```
## Usage

1. **Access the Project**:
    - Open a web browser and go to:
      ```bash
      http://localhost/your-project-name/
      ```
    - Replace `your-project-name` with the actual folder name.

2. **Login or Register**:
    - If your project includes a user authentication system, create an account or log in with an existing one.

3. **Explore the Features**:
    - Follow the on-screen instructions to use the project’s features.

## Features

- List the key features of your project here, e.g.:
  - Login & Check login system
  - CRUD system for Admin
  - Select, Add, Edit, Comments with image for Users

## Contributing

Contributions are welcome! Please fork this repository, create a new branch, and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.
