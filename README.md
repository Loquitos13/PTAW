# Requerimentos para utilizar three.js

Todos os projetos **three.js** necessitam de um ficheiro HTML para definir uma _webpage_, e um ficheiro _JavaScript_ para correr o código **three.js**.
Os nomes dos ficheiro não são importantes.

## Exemplo do ficheiro HTML

```
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>My first three.js app</title>
		<style>
			body { margin: 0; }
		</style>
	</head>
	<body>
		<script type="module" src="/main.js"></script>
	</body>
</html>
```

## Exemplo do ficheiro javascript
```javascript
import * as THREE from 'three';

...
```

É também importante criar uma pasta chamada __public__ de modo a armazenar todo o tipo de dados que serão utilizados pela biblioteca.
Sendo assim a estrutura do projeto terá uma aparência similar a:

```bash
├── index.html
├── main.js
└── public/
   └── modelos3D/
      └── 3DShirt.glb
   ├── texturas/  
      └── textura1
   └── audio/  
      └── audio1
```

## Instalação

Em primeiro lugar é preciso instalar o **[Node.js](https://nodejs.org/en)** para carregar as dependências e correr a _build tool Vite_. 

De seguida instala-se o **three.js** e o **[Vite](https://threejs.org/docs/index.html#manual/en/introduction/Installation)**  utilizando o terminal no diretório do projeto.

```bash
# three.js
npm install --save three

# vite
npm install --save-dev vite
```

Finalmente, apartir do terminal, executamos:

```bash
npx vite
```

Se não houver nenhum problema, o nosso servidor local está criado e ativo, como se pode ver no _URL_: _http://localhost:5173_
