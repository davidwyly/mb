## Summary
This is an example project that transforms POST data of different content types (e.g., XML, JSON) from a third-party system into the following JSON response:

```
{
   "first_name":"Steve", // Patient's first name stored as a string
   "last_name":"Test", // Patient's last name stored as a string
   "external_id":"123", // Unique ID of the patient provided by the EMR
   "date_of_birth":"2000-01-31" // Patient's date of birth
}
```
   
## Installation
These installation instructions assume that the following is already installed on your dev environment:
- Git
- Composer
- Docker
- Docker Compose
- Postman

#### Git
1. Navigate to your home directory
    - `cd ~`
2. Clone down the project
    - `git clone git@github.com:davidwyly/mb`

#### Bash Aliases
1. Ensure you're in the root directory
    - `cd ~/mb`
2. Run `source .bash_aliases`
3. To view all alias commands for this project, run `cat .bash_aliases`

#### Composer
1. Ensure you're in the root directory
    - `cd ~/mb`
2. Composer install
    - `composer install`

#### Docker
1. Open up a new terminal
2. Navigate to the root directory
    - `cd ~/mb`
3. Run `docker-compose up --build`

#### Tests
1. Ensure you're in the root directory
    - `cd ~/mb`
2. Run `run_tests`

#### Postman
1. Import collection from `~/mb/storage/Mb.postman_collection.json`
2. Url for endpoint is `localhost:8080/patient`