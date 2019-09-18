# The Expiration Date Element

This is an element plugin for the custom certificate plugin (https://github.com/markn86/moodle-mod_customcert) for Moodle.

## Installation

This element plugin requires that you already have the custom certificate plugin installed in Moodle.

### Git

This requires Git being installed. If you do not have Git installed, please visit the [Git website](https://git-scm.com/downloads "Git website").

Once you have Git installed, simply visit your Moodle mod/customcert/element directory and clone the repository using the following command.

```
git clone https://github.com/beast2013/moodle-mod_customcert_element_expirationdate.git expirationdate
```

Then checkout the branch corresponding to the version of Moodle you are using with the following command. Make sure to replace MOODLE_36_STABLE with the version of Moodle you are using.

```
git checkout MOODLE_36_STABLE
```

Then log into your Moodle site as an administrator and visit the notifications page to complete the install.

Use `git pull` to update this repository periodically to ensure you have the most recent updates.

## License

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html).