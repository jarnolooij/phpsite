{% extends layouts/master %}

{% block title %}Testing website title!{% block %}

{% block content %}
	the actual content tho
	{ test } 
	{% for array as loop %}
		{ loop } 
	{% endfor %}
{% block %}

{% include includes/footer %}