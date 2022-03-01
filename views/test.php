{% extends layouts/master %}

{% block title %}Testing website title!{% block %}

{% block content %}
	{% for tests as test %}
        { test->name } <br>
    {% endfor %}
{% block %}