# Drupal Agendas and Meetings Module 

## By Jeremy Heminger 2017 

### About

This is part of a module I built for a client: MSRC in Diamond Bar, California.

http://www.cleantransportationfunding.org/

** At the time of this posting our version of this website is in production - slated for release in August 2017

This isn't the full module, its only the Agendas and Meetings part.

I rebranded it for display and it hasn't been tested yet since that change.

Mostly this, as with much of my repository, is simply an augmentation to my resume.

### Licensing etc...

This script currently is for demonstration purposes only. I have not debugged this version since rebranding the file paths.

You are welcome to use and modify this script as you see fit.

### Debug Javascript

The Javascript is minified into _/js/script. To modify the script I suggest modifying the agendas-meetings.libraries.yml file.

Comment out the current js path and uncomment the js paths in the _/components/js folder

### Usage

1. Create a Content Type with the machine name : meetings_agendas_and_minutes
   This can actually be anything as the machine name and the date field machine names can be set in the GUI for each block.

2. Give the type the required fields. (One of the fields must be a Date field type).
   Generally : File for meeting, File for minutes, Field for Active/Cancelled, List (text) for Categories

3. Add a Content Type to display the Blocks. This can contain any content you like.

4. Add as many Agenda and Minutes Blocks as necessary.

5. For each block set Visibility to the Page Content Type added in step 3.

6. For each block set the machine name of the Content Type and the Date field machine name.

7. Don't forget to save your Block Setup.
