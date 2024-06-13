# Assignment 3
- Use my example to implement MVC (MVC must be used going forward) 

- Clone this: https://github.com/mikebiox/cosc-4806-3.git and delete the .git folder and initialize in your own repo

- If you cannot see the .git folder, make sure to enable show hidden files

- Move your code to appropriate php files (model, controllers, or view file)

- Should have a login controller and a home controller, a user model, two views, and possibly more! 

- When a user creates an account, it can be either done in a new controller (create) or use the login controller and a new function (create). It's up to you.

- Create a log table in your database and log all login attempts (good and bad). You'll need to log username, attempt (good, bad), and time

- Also, after 3 unsuccessful login attempts, lock the user out for 60 seconds (based on the time of the last failed attempt)

- Implement basic CSS. In my example, bootstrap is being used but you can delete that and use whatever you want. It doesn't have to look great, just okay

- Everything must be in MVC format. You'll only need to modify the controllers, views, and model files/folders. Nothing else. 
