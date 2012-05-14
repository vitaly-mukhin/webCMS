<!DOCTYPE html>
<html>
	<head>
		{{ Block_Head | default('<!-- here should be the Block_Head -->') | raw }}
	</head>
	<body>
		{{ Block_Login | default('<!-- here should be the Block_Login -->') | raw }}
		{% block head %}
		<h1>Hello, World!</h1>
		{% endblock %}
		<h2>{{ result }}</h2>
	</body>
</html>
