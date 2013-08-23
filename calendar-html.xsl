<?xml version="1.0" encoding="iso-8859-1"?>

<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:param  name="theUser"></xsl:param> 

  <!-- Root -->
  <xsl:template match="calendar">
    <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
        <title>Calendar.</title>
        <link rel="stylesheet" type="text/css" href="mall.css" />
      </head>
    
      <body>
        <!-- Box där hela sidan ligger -->
        <div id="wrapper">
          <div id="head">
            <!-- Box för headerbild-->
            <div id="picture">
              <img src="header.png" width="373" height="77" alt="Header" />
            </div> 
            <!-- end headerbild-->

            <!-- Box för inlogg-->
            <div id="login">
              <h1>
                <xsl:choose>
                <xsl:when test="$theUser != ''"> <!--if logged in-->
                  Logged in as <xsl:value-of select="$theUser"/><br/>
                  <a href="logout.php">Log out</a> 
                </xsl:when>
                <xsl:otherwise><!--if not logged in-->
                  <a href="login.php">Log in</a> 
                </xsl:otherwise>
                </xsl:choose>
              </h1>
            </div> <!-- end inlogg-->
          </div> <!-- end head-->

          <!-- Box för meny-->
          <div id="menu">
              <a href="index.php">Start | </a> 
              <a href="schedule.php">Schedule | </a> 
              <a href="myschedule.php">My schedule | </a> 
              <a href="newactivity.php">New acitivity | </a> 
              <a href="groups.php">Groups</a> 
              <div id="search">
                <form method="post" action="search.php">
                  <input type="search" name="search"/>
                </form>
              </div>
          </div> <!-- end meny-->

          <div id="text">
            <xsl:apply-templates />
          </div> <!-- end textruta-->

        </div> <!-- end wrapper-->
      </body>
     </html>
  </xsl:template>

  <!-- Group -->
  <xsl:template match="group">
    <h1><xsl:value-of select="name"/></h1>
    <ul>
      <xsl:apply-templates/> 
    </ul>
  </xsl:template>

  <!-- Login form from login.php-->
  <xsl:template match="form[@name='login']"> 
    <div class="styleform">
      <h1>Log in</h1>
      <form method="post" action="check.php">
        <label>Username: </label><input type="text" name="username"/><br/>
        <label>Password: </label><input type="password" name="password"/><br/>
        <input type="submit" class="formSubmit" name="formSubmit" value="Log in"/>
      </form>
    </div>

    <div class="styleform">
      <h1>New user</h1>
      <form method="post" action="check.php">
        <label>Username: </label><input type="text" name="username"/><br/>
        <label>Password: </label><input type="password" name="password"/><br/>
        <label>Repeat password: </label><input type="password" name="password2"/><br/>
        <label>Email: </label><input type="email" name="email"/><br/>
        <input type="submit" class="formSubmit" name="formNew" value="Register"/>
      </form>
    </div>
  </xsl:template>

  <!-- New activity form from newactivity.php-->
  <xsl:template match="form[@name='new']"> 
    <xsl:choose>
      <xsl:when test="$theUser != ''"> <!--if logged in-->
        <div class="styleform">
          <form method="post" action="insert.php">
            <label>Name: </label><input type="text" name="activityName"/><br/>
            <label>Date: </label><input type="date" name="date"/><br/>
            <label>Time: </label><input type="time" name="starttime"/> <input type="time" name="endtime"/><br/>
            <label>Place: </label><input type="text" name="place"/><br/>
            <label>Created by: </label><input type="text" name="creator" value="{$theUser}" readonly="readonly" /><br/>
            <label>Link: </label><input type="text" name="link"/><br/>
            <label>Other info: </label><input type="text" name="info"/><br/>
            <label>Group: </label>
            <xsl:apply-templates select="select"/><br/>
            <input type="submit" class="formSubmit"  name="formCreate" value="Create"/>
          </form>
        </div>
      </xsl:when>
      <xsl:otherwise><!--if not logged in-->
        You must be logged in to create a new activity
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <!-- Edit activity from activity.php -->
  <xsl:template match="form[@name='edit']"> 
    <xsl:choose>
      <xsl:when test="$theUser != ''"> <!--if logged in-->
        <div class="styleform">
          <form method="post" action="insert.php">
            <!-- Send original activity name to form -->
            <xsl:choose>
            <xsl:when test="input/@name = 'activityName'"> <!--if logged in-->
              <input type="hidden" name="oName" value="{input/@value}" />
            </xsl:when>
            </xsl:choose>

            <!-- Rest of form -->
            <xsl:apply-templates select="label|input"/><br/>
            <xsl:apply-templates select="select"/><br/>

            <!-- Submit button -->
            <input type="submit" class="formSubmit"  name="formSave" value="Save"/>
          </form>
        </div>
      </xsl:when>
      <xsl:otherwise><!--if not logged in-->
        You must be logged in to edit an activity
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

    <!-- Form for new group-->
  <xsl:template match="form[@name='group']"> 
    <xsl:choose>
      <xsl:when test="$theUser != ''"> <!--if logged in-->
        <div class="styleform">
          <h1>Create new group with members</h1>
          <form method="post" action="insert.php">
            <label>Group name: </label><input type="text" name="group"/><br/>
            <label>Users: </label><input type="text" name="users"/><br/>
             Seperate users with ,<br/>
            <input type="submit" class="formSubmit"  name="formGroup1" value="Create"/>
          </form>
        </div>
        <div class="styleform">
          <h1>Add members to group</h1>
          <form method="post" action="insert.php">
            <label>Group name: </label><xsl:apply-templates select="select"/><br/>
            <label>Users: </label><input type="text" name="users"/><br/>
            Seperate users with ,<br/>
            <input type="submit" class="formSubmit"  name="formGroup2" value="Save"/>
          </form>
        </div>
      </xsl:when>
      <xsl:otherwise><!--if not logged in-->
        You must be logged in to handle groups.
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <!-- Form label -->
  <xsl:template match="label"> 
    <label><xsl:value-of select="."/></label>
  </xsl:template>

  <!-- Form input -->
  <xsl:template match="input"> 
    <xsl:variable name="inputname"><xsl:value-of select="@name"/> </xsl:variable>
    <xsl:choose>
      <xsl:when test="$inputname = 'creator'"> <!--if creator box-->
        <input type="{@type}" name="{@name}" value="{@value}" readonly="readonly" />
      </xsl:when>
      <xsl:otherwise><!--if not selected-->
        <input type="{@type}" name="{@name}" value="{@value}"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <!-- Form select -->
  <xsl:template match="select"> 
    <select name='group'>
      <xsl:apply-templates select="option"/>
    </select>
  </xsl:template>

  <!-- Form option -->
  <xsl:template match="option"> 
    <xsl:variable name="selected"><xsl:value-of select="@selected"/></xsl:variable>
    <xsl:choose>
      <xsl:when test="$selected = 'selected'"> <!--if selected-->
        <option value='{@value}' selected="{@selected}">
        <xsl:value-of select="@value"/>
      </option>
      </xsl:when>
      <xsl:otherwise><!--if not selected-->
        <option value='{@value}'>
        <xsl:value-of select="@value"/>
      </option>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>


  <!-- Message to screen div -->
  <xsl:template match="div"> 
   <xsl:value-of select="@value"/>
  </xsl:template>

  <!-- Subscribe to activity from activity.php-->
  <xsl:template match="subscription">
    <h1><xsl:value-of select="name"/></h1>
    <ul>
      <xsl:apply-templates/> 
    </ul>
  </xsl:template>

  <!-- User -->
  <xsl:template match="user">
    <h1><xsl:value-of select="name"/></h1>
    <ul>
      <xsl:apply-templates/> 
    </ul>
  </xsl:template>
  
  <!-- Activity -->
  <xsl:template match="activity">
    <xsl:variable name="user"><xsl:value-of select="creator/user/name"/> </xsl:variable>
    <div class="styleactivity">
      <h1><xsl:value-of select="name"/>
      <form method="post" action="activity.php">
        <input type="hidden" name="activityName" value="{name}"/>

        <xsl:choose>
          <xsl:when test="$theUser != '' and $currentPage != 'myschedule.php'"> <!--if logged in and current page not myschedule-->
            <input type="submit" class="button" name="button" value="Subscribe"/>
          </xsl:when>
          <xsl:otherwise><!--if not logged in OR already subscribing-->
            <input type="submit" class="button" name="button" value="Subscribe" disabled="disabled"/>
          </xsl:otherwise>
        </xsl:choose>

        <xsl:choose>
          <xsl:when test="$theUser = $user"> <!--if logged in-->
            <input type="submit" class="button" name="button2" value="Edit"/>
          </xsl:when>
          <xsl:otherwise><!--if not logged in-->
            <input type="submit" class="button" name="button2" value="Edit" disabled="disabled"/>
          </xsl:otherwise>
        </xsl:choose>
      </form></h1>

      Name : <xsl:value-of select="name"/> <br/>
      Date : <xsl:value-of select="date"/> <br/>
      Time: <xsl:value-of select="starttime"/>:<xsl:value-of select="endtime"/>  <br/>
      Place: <xsl:value-of select="place"/> <br/>
      Created by: <xsl:value-of select="creator/user/name"/> <br/>
      Link: <a href="{link}" target="_blank">Webpage</a> <br/>
      Other info: <xsl:value-of select="info"/><br/>
      Group: <xsl:value-of select="group/name"/><br/>
    </div>
  </xsl:template>

</xsl:stylesheet>

