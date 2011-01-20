<h1>Create a new page for your child</h1>

<div class="clearfix">
  <?php getTemplate()->display('partials/paragraphRight.php'); ?>

  <p>
    <form method="post" action="/child/new" id="childNewForm">
      <label for="childName">Child's name</label>
      <input type="text" name="childName" required="required">

      <label for="childBirthDate">Birthdate &amp; time<em>(this format 6/19/2010 at 10:06 am)</em></label>
      <input type="text" name="childBirthDate" date="mm/dd/yyyy">

      <label for="childDomain">Web page URL<em>(http://johnny.meltsmyheart.com - just enter johnny)</em></label>
      <input type="text" name="childDomain" required="required" check-domain="true" check-name="">

      <button class="yellow" type="submit"><div>Submit</div></button>
    </form>
  </p>
</div>
