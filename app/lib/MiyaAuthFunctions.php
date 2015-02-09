<?php
//Functions related to authentication
function isLoggedin()
{
	return Sentry::check();
}

function getAuthUser()
{
	return  Sentry::getUser();
}

function isSuperAdmin()
{
	if(Sentry::getUser())
	{
		return (Sentry::getUser()->user_access == 'Admin');
	}
	return false;
}

function hasAdminAccess()
{
	if(isSuperAdmin())
	{
		return true;
	}
	return false;
}