<a href="https://codeclimate.com/github/davidwyly/mb"><img src="https://codeclimate.com/github/davidwyly/mb/badges/gpa.svg" /></a></a>
<a href="https://codeclimate.com/github/davidwyly/mb"><img src="https://codeclimate.com/github/davidwyly/mb/badges/issue_count.svg" /></a> [![Code Climate](https://img.shields.io/codeclimate/tech-debt/davidwyly/mb.svg)](https://codeclimate.com/github/davidwyly/mb) 





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

## Project Structure
- Environment variables go into `/config/.env` and newly-required `.env` definitions are checked into `/config/bootstrap.php`
- Routes for all endpoints are declared in `/public/index.php`
- PSR4 namespacing is used in the `src` directory for all other project-related files
    - e.g., The `Davidwyly\Mb\Http\Controller\PatientController` class is located at `/src/Davidwyly/Mb/Http/Controller/PatientController.php`
- Tests are located in `/tests`
    - Test fixtures in `/tests/fixtures`
    - Mock objects are located in `/src/Davidwyly/Mb/Mock`

## Installation
While this project does not have to be installed in order to be evaluated, I have set up docker so that you can more easily run and test the service yourself.

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
3. Build your containers
   - `docker-compose up --build`

#### Tests
1. Ensure you're in the root directory
    - `cd ~/mb`
2. Run the PHPUnit tests
   - `run_tests`

#### Postman
1. Import collection from `~/mb/storage/Mb.postman_collection.json`
2. Set up a new postman environment
   1. Click the gear icon in top-right corner
   2. Click `Add`
   3. Create an environment name
   4. Add a `url` variable with `localhost:8080` as the initial value
   5. Click `Add`
   6. Select the environment from the drop-down in the top-right corner
3. Requests can be found within the `Mb` collection
