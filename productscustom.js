/*
TODO:
- Layout similar to figma
- Connect to DB
- Zoom bugs
- Decal placement bugs
- Admin check model with decal
- Limit image type, size and dimensions
- Images go over the limit of the modal
*/

// check for remove

import * as THREE from 'three';

import WebGL from 'three/addons/capabilities/WebGL.js'; // import to see it device/browser supports WebGl 2

import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js'; // import own 3D models

import { OrbitControls } from 'three/addons/controls/OrbitControls.js'; // import to rotate the GLTF model using mouse click and drag

import { DecalGeometry } from 'three/addons/geometries/DecalGeometry.js'; // import to place image on top of model

import { GLTFExporter } from 'three/addons/exporters/GLTFExporter.js'; // import to export the scene


let logo, decalPlaced = false;
let scene, camera, renderer, controls, light;
let loader, model, box, center, size;
let mouse, raycaster, helper, customDecal, decalTexture, decalMaterial;


document.getElementById("openModal").addEventListener("click", function () {
    document.getElementById("modal3D").style.display = "flex";
    document.getElementById("modalImage").style.display = "flex";
});

document.getElementById("closeModal").addEventListener("click", function () {
    document.getElementById("modal3D").style.display = "none";
    document.getElementById("modalImage").style.display = "none";
});

document.addEventListener("DOMContentLoaded", function () {
    const label = document.getElementById("uploadImage");
    const fileInput = label.querySelector("input[type='file']");

    fileInput.addEventListener("change", function (event) {

        if (event.target.files && event.target.files[0]) {

            const reader = new FileReader();
            reader.onload = function (e) {

                reader.src = URL.createObjectURL(event.target.files[0]); // set src to blob url

                logo = reader.src;
    
                label.innerHTML = `<img id="myImg" src="${e.target.result}" alt="Uploaded Image" style="max-width: 50%; height: 50%;">`;

                checkSupport();

            };

            reader.readAsDataURL(event.target.files[0]);
            removeClass("btnReplaceImage", "hideButton");
           
        }
    })
})

document.addEventListener("DOMContentLoaded", function () {
    const label = document.getElementById("btnReplaceImage");
    const fileInput = label.querySelector("input[type='file']");

    fileInput.addEventListener("change", function (event) {

        if (event.target.files && event.target.files[0]) {

            const reader = new FileReader();
            reader.onload = function (e) {

                reader.src = URL.createObjectURL(event.target.files[0]); // set src to blob url

                logo = reader.src;

                let newSrc = document.getElementById("myImg");

                newSrc.src = e.target.result; 

                checkSupport();

            };

            reader.readAsDataURL(event.target.files[0]);
           
        }
    })
})

function removeClass(id, className) {

    let element = document.getElementById(id);
    element.classList.remove(className);
}

function addClass(id, className) {

    let element = document.getElementById(id);
    element.classList.add(className);
}

function checkSupport() {

    if ( WebGL.isWebGL2Available() ) {

        initThreeJS();

    } else {

        const warning =  WebGL.getWebGL2ErrorMessage();
        document.getElementById('modal3D').appendChild(warning);

        alert(`The device or the browser don't support this functionality! \n Please reconsider updating. \n Error: ${warning}`);

    }
}

function initThreeJS() {

    const canvas = document.getElementById("modelCanvas");
    const loadingText = document.getElementById("loading");

    // Scene
    scene = new THREE.Scene();

    // Camera
    camera = new THREE.PerspectiveCamera(50, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);

    // Renderer
    renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    
    // Adding OrbitControls
    controls = new OrbitControls( camera, renderer.domElement );
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.rotateSpeed = 0.5;
    controls.zoomSpeed = 1.2;
    controls.minDistance = 1;
    controls.maxDistance = 1.5;
    controls.enablePan = true;

    // Light
    light = new THREE.AmbientLight(0xffffff, 1);
    scene.add(light);

    // Adding own model to the scene
    loader = new GLTFLoader();

    loader.load('modelos3D/3D_Shirt.glb', function(gltf){

        model = gltf.scene;

        scene.add(model);

        // Center model
        box = new THREE.Box3().setFromObject( model );
        center = box.getCenter( new THREE.Vector3() );

        // Place the model in the center
        model.position.sub( center );

        // Move camera to fit model
        size = box.getSize( new THREE.Vector3()).length();
        camera.position.set( 0, size * 0.5, size * 1.5 );
        camera.lookAt(model.position);


        addCustomDecal();


    }, undefined, function(error){

        alert(`Error loading the model! \n Please try again. \n Error: ${error}`);

    });

    // Hide loading text when model is ready
    setTimeout(() => {
        loadingText.style.display = "none";
    }, 500); 

    animate();

    // Resize Handling
    window.addEventListener("resize", () => {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });

}

// Animate the model
function animate() {

    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);

}

// Add decal to Model
function addCustomDecal() {

    // Decal
    mouse = new THREE.Vector2();
    raycaster = new THREE.Raycaster();
    helper = new THREE.Object3D();

    // Load the custom decal
    customDecal = new THREE.TextureLoader();
    decalTexture = customDecal.load(logo);

    document.addEventListener('click', onClick);

}

function onClick(event){

    event.preventDefault();

    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);

    let intersects = raycaster.intersectObject(model, true);

    if (intersects.length > 0) {

        let intersectedMesh = intersects[0].object; // Get the specific mesh
        let position = intersects[0].point;
        let normal = intersects[0].face.normal.clone();
        normal.transformDirection(intersectedMesh.matrixWorld);
        normal.add(position);
        helper.position.copy(position);
        helper.lookAt(normal);
    
        let size = new THREE.Vector3(0.3, 0.3, 0.3);
        let decalGeometry = new DecalGeometry(intersectedMesh, position, helper.rotation, size);
        decalMaterial = new THREE.MeshStandardMaterial({
            map: decalTexture,
            transparent: true,
            depthTest: true,
            depthWrite: false,
            polygonOffset: true,
            polygonOffsetFactor: -4,
        });

        if (!decalPlaced) {

            let decal = new THREE.Mesh(decalGeometry, decalMaterial);
            scene.add(decal);

            decalPlaced = true;

            removeClass("rmvDecal", "disableButton");
            removeClass("btnAdd", "disableButton");


            addToCart();


            document.querySelectorAll(".removeDecal").forEach(button => {
                button.addEventListener("click", function() {
 
                    scene.remove(decal);
                    decalPlaced = false;

                    //button.removeEventListener();
                    addClass("rmvDecal", "disableButton");
                    addClass("btnAdd", "disableButton");
            
                });
            });



        } else {

            alert("Limit to only one decal!");

        }
    }
}

function addToCart() {

    document.querySelectorAll(".buttonAdd").forEach(button => {
        button.addEventListener("click", function() {
    
            const exporter = new GLTFExporter();
            exporter.parse(
              scene,
              function (result) {
                saveArrayBuffer(result, 'scene.glb');
              },
              { binary: true }
            );

        });
    });

    function saveArrayBuffer(buffer, filename) {
        save(new Blob([buffer], { type: 'application/octet-stream' }), filename);
    }

    const link = document.createElement('a');
    link.style.display = 'none';
    document.body.appendChild(link); // Firefox workaround, see #6594
    
    function save(blob, filename) {
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }
}