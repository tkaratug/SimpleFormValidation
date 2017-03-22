# Simple Form Validation

PHP data validation class that makes validating easy.

# Installing
- Download Repository,
- Unzip and copy Validation.php to PHP project directory.

# Usage
1- Include the class into your project.
```php
include 'validation.php';
$validator = new Validation();

$validator->set_rules([
    'fullname'  => 'required|min_len,3',
    'email'     => 'required|email',
    'age'       => 'required|numeric',
    'website'   => 'valid_url'
]);

$validator->set_data([
    'fullname'  => $_POST['fullname'],
    'email'     => $_POST['email'],
    'age'       => $_POST['age'],
    'website'   => $_POST['website'],
]);

if($validator->is_valid() !== true) {
	foreach($validator->errors as $error) {
		echo $error .'<br>';
	}
}
```

# Available Validators
- **required** ```the field is required```
- **numeric** ```the field must be numeric```
- **email** ```the field must be valid email```
- **min_len** ```the field's length must be minimum specified length```
- **max_len** ```the field's length must be maximum specified length```
- **exact_len** ```the field's length must be specified length```
- **alpha** ```the field must contain only alpha characters```
- **alpha_num** ```the field must contain only alphanumeric characters```
- **alpha_dash** ```the field must contain only alphanumeric characters, dashes and underscores```
- **alpha_space** ```the field must contain only alphanumeric characters and numbers```
- **integer** ```the field must be integer```
- **boolean** ```the field must be boolean```
- **float** ```the field must be float```
- **valid_url** ```the field must be valid url```
- **valid_ip** ```the field must be ip address```
- **valid_ipv4** ```the field must be ipv4 format```
- **valid_ipv6** ```the field must be ipv6 format```
- **valid_cc** ```the field must be valid credit card number```
- **contains** ```the field must contain specified characters```
- **min_numeric** ```the field must be minimum specified value```
- **max_numeric** ```the field must be maximum specified value```
- **matches** ```the field must match with specified field```
