import * as THREE from 'three';

import WebGL from 'three/addons/capabilities/WebGL.js'; // import to see it device/browser supports WebGl 2

import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js'; // import own 3D models

import { OrbitControls } from 'three/addons/controls/OrbitControls.js'; // import to rotate the GLTF model using mouse click and drag

import { DecalGeometry } from 'three/addons/geometries/DecalGeometry.js'; // import to place image on top of model

if ( WebGL.isWebGL2Available() ) { 

    // Limit to one decal
    let decalPlaced = false;

    // Creating the Scene
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 5000 );

    const renderer = new THREE.WebGLRenderer();
    renderer.setSize( window.innerWidth, window.innerHeight );
    document.body.appendChild( renderer.domElement );

    // Adding OrbitControls
    const controls = new OrbitControls( camera, renderer.domElement );
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.rotateSpeed = 0.5;
    controls.zoomSpeed = 1.2;
    controls.enablePan = false;

    // Adding light
    const light = new THREE.AmbientLight(0xffffff, 1)
    scene.add(light);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
    directionalLight.position.set( 5, 5, 5 ).normalize();
    scene.add(directionalLight);

    // Load the custom decal
    const customDecal = new THREE.TextureLoader();
    const decalTexture = customDecal.load( './public/logo.png' );

    // Raycaster and mouse vector to get mouse click position
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();

    // Adding own model to the scene
    const loader = new GLTFLoader();

    loader.load( './public/modelos3D/3D_Shirt.glb', function ( gltf ) {

        const model = gltf.scene;

        console.log( "Model loaded", gltf );
        scene.add( model );

        model.traverse( child => { 

            if ( child.isMesh ) {

                child.raycast = THREE.Mesh.prototype.raycast;
                child.material.side = THREE.DoubleSide;

            }
        });

        // Find the first valid mesh in the model
        let targetMesh = null;
        model.traverse( ( child ) => {

            if ( child.isMesh ) {

                targetMesh = child;

            }

        });

        if ( !targetMesh ) {
            console.error( "No valid mesh found inside the model." );
            return;
        }

        // Center model
        const box = new THREE.Box3().setFromObject( model );
        const center = box.getCenter( new THREE.Vector3() );

        // Place the model in the center
        model.position.sub( center );

        // Move camera to fit model
        const size = box.getSize( new THREE.Vector3()).length();
        camera.position.set( 0, size * 0.5, size * 1.5 );
        camera.lookAt( model.position);

        // Event listener for mouse clicks
        window.addEventListener( 'pointerdown', onPointerDown );

        function onPointerDown( event ) {

            if ( !model ) {

                console.warn( "Model not loaded yet!" );
                return;

            }

            // Get mouse coordinates
            mouse.x = ( event.clientX / window.innerWidth ) * 2 - 1;
            mouse.y = - ( event.clientY / window.innerHeight ) * 2 + 1;
        
            // Cast ray from camera through the scene
            raycaster.setFromCamera( mouse, camera );
            const intersects = raycaster.intersectObjects( model.children, true );

            console.log("Mouse Clicked:", mouse);
            console.log("Raycaster Intersections:", intersects);
        
            if ( intersects.length > 0 ) {

                const intersection = intersects[0];
            
                console.log("Intersection Point:", intersection.point);
                console.log("Intersection Normal:", intersection.normal);


                if ( !decalPlaced ) {

                    placeDecal( intersection.point, intersection.normal, intersection.object );

                    decalPlaced = true;

                } else {

                    console.warn( "There is already a decal!" );

                }

            } else {

                console.warn( "No intersection detected with model." );

            }

        }
    
        function placeDecal( position, normal, object ) {
    
            if ( !object || !object.isMesh ) {

                console.error( "Invalid mesh for decal placement." );
                return;

            }

            console.log( "Placing decal on mesh: ", object.name || object.id );

            // Move decal to avoid z-fighting
            const decalPosition = position.clone().add(normal.clone().multiplyScalar(0.01));
    
            // Compute a stable orientation to prevent stretching
            const orientation = new THREE.Quaternion();
            const up = new THREE.Vector3( 0, 1, 0 );
            orientation.setFromRotationMatrix( new THREE.Matrix4().lookAt( normal, new THREE.Vector3( 0, 0, 0 ), up ) );
    
            // Decal size
            const decalSize = new THREE.Vector3( 0.3, 0.3, 0.3 );
    
            // Create the decal geometry
            const decalGeometry = new DecalGeometry( object, decalPosition, orientation, decalSize );
            const decalMaterial = new THREE.MeshStandardMaterial( {
                map: decalTexture,
                transparent: true,
                depthTest: true,
                depthWrite: false,
                polygonOffset: true,
                polygonOffsetFactor: -4,
            });
    
            const decalMesh = new THREE.Mesh( decalGeometry, decalMaterial );
            scene.add( decalMesh );

            document.querySelectorAll(".refreshButton").forEach(button => {
                button.addEventListener("click", function() {
 
                    scene.remove( decalMesh );
                    decalPlaced = false;
            
                });
            });
            
    
        }

        // Adding wheel zoom
        document.addEventListener( 'wheel', (event) => {

            camera.position.z += event.deltaY * 0.01;
            camera.position.z = Math.max( 1, Math.min( camera.position.z, 10 ));

        } )

    }, undefined, function ( error ) {

        console.error( "Error loading model: ", error );

    } );

    // Rendering the Scene
    function animate() {

        requestAnimationFrame( animate );
        controls.update();
        renderer.render( scene, camera );

    }
    animate();

} else {

    const warning =  WebGL.getWebGL2ErrorMessage();
    document.getElementById( 'container' ).appendChild( warning );

}
