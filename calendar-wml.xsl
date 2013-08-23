<?xml version="1.0" encoding="iso-8859-1"?>

<xsl:stylesheet version="1.0" 
   xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> 

  <!-- Root -->
  <xsl:template match="calendar">
    <wml>
      <template>
        <!-- Search box -->
        <onevent type="onenterforward">
          <refresh>
          </refresh>
        </onevent>
        
        <p>
          <input type="search" name="search"/><br/>
          <anchor>
            <go method="post" href="search.php">
              <postfield name="search" value="$(search)"/>
            </go>
            Search
          </anchor>
        </p>

        <!-- Menu -->
        <do label="Back" type="accept"><prev/></do>
        <do name="menu2" type="accept" label="Schedule">
          <go href="schedule.php"/>
        </do>
        <do name="menu3" type="accept" label="My schedule">
          <go href="myschedule.php#div"/>
        </do>
        <do name="menu4" type="accept" label="New activity">
          <go href="newactivity.php#new"/>
        </do>
         <do name="menu5" type="accept" label="Groups">
          <go href="groups.php#edit"/>
        </do>
        <xsl:variable name="user"><xsl:value-of select="$theUser"/> </xsl:variable>
        <xsl:choose>
          <xsl:when test="$user != ''"> <!--if logged in-->
            <do name="menu6" type="accept" label="Logged in as {$user}, Log out">
              <go href="logout.php"/>
            </do>
          </xsl:when>
          <xsl:otherwise><!--if not logged in-->
            <do name="menu1" type="accept" label="Log in">
              <go href="login.php#login"/>
            </do>
          </xsl:otherwise>
        </xsl:choose>

      </template>
      <!-- Main card -->
      <card id="main" title="Calendar." >
        <xsl:for-each select="activity"><!-- All activities -->
          <p><a href="#activity{position()}"><xsl:value-of select="name"/></a></p>
        </xsl:for-each>
      </card>
      <xsl:apply-templates/>
    </wml>
  </xsl:template>

  <!-- Activity -->
  <xsl:template match="activity">
    <xsl:variable name="user"><xsl:value-of select="creator/user/name"/> </xsl:variable>
    <card>
    <xsl:attribute name="id">activity<xsl:number/></xsl:attribute>
    <xsl:attribute name="title"><xsl:value-of select="name"/></xsl:attribute>

    <!-- Buttons to subscribe and edit -->
    <onevent type="onenterforward">
      <refresh>
        <setvar name="button" value="Subscribe"/>
        <setvar name="button2" value="Edit"/>
      </refresh>
    </onevent>
    <p>
      <input type="hidden" name="activityName"/>
      <xsl:choose>
        <xsl:when test="$theUser != '' and $currentPage != 'myschedule.php'"> <!--if logged in and current page not myschedule-->
          <anchor>
            <go method="post" href="activity.php#div">
              <postfield name="activityName" value="{name}"/>
              <postfield name="button" value="$(button)"/>
            </go>
            Subscribe
          </anchor>
        </xsl:when>
      </xsl:choose>

      <xsl:choose>
        <xsl:when test="$theUser = $user"> <!--if you created the event-->
          <anchor>
            <go method="post" href="activity.php#edit">
              <postfield name="activityName" value="{name}"/>
              <postfield name="button2" value="$(button2)"/>
            </go>
            Edit
          </anchor>
        </xsl:when>
      </xsl:choose>
    </p>

     <p>
      Name : <xsl:value-of select="name"/> <br/>
      Date : <xsl:value-of select="date"/> <br/>
      Time: <xsl:value-of select="starttime"/>:<xsl:value-of select="endtime"/>  <br/>
      Place: <xsl:value-of select="place"/> <br/>
      Created by: <xsl:value-of select="creator/user/name"/> <br/>
      Link: <a href="{link}" target="_blank">Webpage</a> <br/>
      Other info: <xsl:value-of select="info"/><br/>
      Group: <xsl:value-of select="group/name"/><br/>
      </p>
    </card>
  </xsl:template>

  <!-- Login form from login.php-->
  <xsl:template match="form[@name='login']"> 
    <card id="card1" title="WML Form">
     <xsl:attribute name="id">login</xsl:attribute>
      
    <onevent type="onenterforward">
      <refresh>
        <setvar name="formSubmit" value="Log in"/>
        <setvar name="formNew" value="Register"/>
      </refresh>
    </onevent>

    <p>
      <!-- Existing user-->
      <big>Log in</big><br/>
      Username<br/><input type="text" name="username"/><br/>
      Password<br/><input type="password" name="password"/><br/>

     
      <anchor>
        <go method="post" href="check.php">
          <postfield name="username" value="$(username)"/>
          <postfield name="password" value="$(password)"/>
          <postfield name="formSubmit" value="$(formSubmit)"/>
        </go>
        Log in
      </anchor>
    </p>

    <p>
      <!-- New user-->
      <big>New user</big><br/>
      Username<br/><input type="text" name="username"/><br/>
      Password<br/><input type="password" name="password"/><br/>
      Repeat password<br/><input type="password" name="password2"/><br/>
      Email<br/><input type="email" name="email"/><br/>

      <anchor>
        <go method="post" href="check.php">
          <postfield name="username" value="$(username)"/>
          <postfield name="password" value="$(password)"/>
          <postfield name="password2" value="$(password2)"/>
          <postfield name="email" value="$(email)"/>
          <postfield name="formNew" value="$(formNew)"/>
        </go>
        Register
      </anchor>
      </p>
    </card>
  </xsl:template>

   <!-- New activity form from newactivity.php-->
  <xsl:template match="form[@name='new']"> 

     <card title="New activity" id="new">
      <xsl:choose>
        <xsl:when test="$theUser != ''"> <!--if logged in-->
          <onevent type="onenterforward">
            <refresh>
              <setvar name="formCreate" value="Create"/>
            </refresh>
          </onevent>

          <p>
             <!-- Rest of form -->
            Name: <input type="text" name="activityName"/><br/>
            Date: <input type="date" name="date"/><br/>
            Time: <input type="time" name="starttime"/> <input type="time" name="endtime"/><br/>
            Place: <input type="text" name="place"/><br/>
            Created by: <input type="text" name="creator" value="{$theUser}" readonly="readonly" /><br/>
            Link: <input type="text" name="link"/><br/>
            Other info: <input type="text" name="info"/><br/>
            Group:  <xsl:apply-templates select="select"/><br/>
         
            <anchor>
              <go method="post" href="insert.php">
                <postfield name="formCreate" value="$(formCreate)"/>
                <postfield name="activityName" value="$(activityName)"/>
                <postfield name="date" value="$(date)"/>
                <postfield name="starttime" value="$(starttime)"/>
                <postfield name="endtime" value="$(endtime)"/>
                <postfield name="place" value="$(place)"/>
                <postfield name="creator" value="$(creator)"/>
                <postfield name="link" value="$(link)"/>
                <postfield name="info" value="$(info)"/>
                <postfield name="group" value="$(group)"/>
              </go>
              Save
            </anchor>
          </p>
        </xsl:when>
        <xsl:otherwise><!--if not logged in-->
          You must be logged in to create a new activity
        </xsl:otherwise>
      </xsl:choose>
    </card>
  </xsl:template>

  <!-- Edit activity from activity.php -->
  <xsl:template match="form[@name='edit']"> 
    <card title="{input/@value}" id="edit">
      <xsl:choose>
        <xsl:when test="$theUser != ''"> <!--if logged in-->
          <onevent type="onenterforward">
            <refresh>
              <setvar name="formSave" value="Save"/>
                <xsl:for-each select="input">
                  <setvar name="{@name}" value="{@value}"/>
                </xsl:for-each>
            </refresh>
          </onevent>
          <onevent type="onenterbackward">
            <refresh>
              <xsl:for-each select="input">
                <setvar name="{@name}" value="{@value}"/>
              </xsl:for-each>
            </refresh>
          </onevent>

          <p>
            <!-- Original name -->
            <input type="hidden" name="oName"/><br/>
             <!-- Rest of form -->
            <xsl:apply-templates select="label|input"/><br/>
            <xsl:apply-templates select="select"/><br/>
         
            <anchor>
              <go method="post" href="insert.php">
                <postfield name="oName" value="{input/@value}"/>
                <postfield name="formSave" value="$(formSave)"/>
                <xsl:for-each select="input">
                  <postfield name="{@name}" value="$({@name})"/>
                </xsl:for-each>
                <postfield name="group" value="$(group)"/>
              </go>
              Save
            </anchor>
          </p>
        </xsl:when>
        <xsl:otherwise><!--if not logged in-->
          You must be logged in to edit an activity.
        </xsl:otherwise>
      </xsl:choose>
    </card>
  </xsl:template>

  <!-- Form for new group-->
  <xsl:template match="form[@name='group']"> 
    <card title="Groups" id="edit">
      <xsl:choose>
        <xsl:when test="$theUser != ''"> <!--if logged in-->
                <onevent type="onenterforward">
            <refresh>
              <setvar name="formGroup1" value="Create"/>
              <setvar name="formGroup2" value="Save"/>
            </refresh>
          </onevent>

          <p>
             <!-- New group -->
             <big>Create new group with members</big><br/>
              Group name: <input type="text" name="group"/><br/>
              Users: <input type="text" name="users"/><br/>
              Seperate users with ,<br/>
         
            <anchor>
              <go method="post" href="insert.php">
                <postfield name="formGroup1" value="$(formGroup1)"/>
                <postfield name="group" value="$(group)"/>
                <postfield name="users" value="$(users)"/>
              </go>
              Create
            </anchor>
          </p>

          <p>
             <!-- Add to existing -->
             <big>Add members to group</big><br/>
              Group name: <xsl:apply-templates select="select"/><br/>
              Users: <input type="text" name="users"/><br/>
              Seperate users with ,<br/>
         
            <anchor>
              <go method="post" href="insert.php">
                <postfield name="formGroup2" value="$(formGroup2)"/>
                <postfield name="group" value="$(group)"/>
                <postfield name="users" value="$(users)"/>
              </go>
              Save
            </anchor>
          </p>

        </xsl:when>
        <xsl:otherwise><!--if not logged in-->
          You must be logged in to handle groups.
        </xsl:otherwise>
      </xsl:choose>
    </card>
  </xsl:template>

  <!-- Form input -->
  <xsl:template match="input"> 
    <xsl:variable name="inputname"><xsl:value-of select="@name"/> </xsl:variable>
    <xsl:choose>
      <xsl:when test="$inputname = 'creator'"> <!--if selected-->
        <input type="{@type}" name="{@name}" size="30" readonly="readonly">
          <xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute>
        </input>
        <br/>
      </xsl:when>
      <xsl:otherwise><!--if not selected-->
        <input type="{@type}" name="{@name}" size="30">
          <xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute>
        </input>
        <br/>
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

  <!-- Form label -->
  <xsl:template match="label"> 
   <xsl:apply-templates/>
  </xsl:template>

  <!-- Message to screen div -->
  <xsl:template match="div"> 
    <card id="div" title="Message">
      <xsl:value-of select="@value"/>
    </card>
  </xsl:template>

</xsl:stylesheet>


