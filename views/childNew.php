<h1>Create a new page for your child</h1>

<?php getTemplate()->display('partials/paragraphRight.php'); ?>

<p>
  <form method="post" action="/child/new" id="childNewForm">
    <label for="childName">Child's name</label>
    <input type="text" name="childName" required="required">

    <label for="childBirthDate">Birthdate <em>(mm/dd/yyyy)</em></label>
    <input type="text" name="childBirthDate" date="mm/dd/yyyy">

    <label for="childDomain">Web page address <em>(http://somename.meltsmyheart.com)</em></label>
    <input type="text" name="childDomain" required="required" check-name="">

    <button class="yellow" type="submit"><div>Submit</div></button>
  </form>
</p>

