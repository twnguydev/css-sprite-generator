<?php
/**
 * Génère une animation ThreeJS
 * => crée un fichier three.js
 * => crée un fichier dataImages.js
 * => crée un fichier three.js à lancer dans LiveServer
 * 
 * @param array $images         Tableau des images sauvegardées
*/

function threeAnimation($images) {
    $data = "export const imageData = [\n";

    foreach ($images as $i => $image) {
        $data .= "  {\n";
        $data .= "      id: " . $i . ",\n";
        $data .= "      path: '../" . $image . "',\n";
        $data .= "  },\n";
    }

    $data .= "];";


    $html_ct = "<!DOCTYPE html>\n";
    $html_ct .= "<html lang=\"fr\">\n";
    $html_ct .= "<head>\n";
    $html_ct .= "  <meta charset=\"UTF-8\">\n";
    $html_ct .= "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    $html_ct .= "  <title>ThreeJS Animation</title>\n";
    $html_ct .= "  <script type=\"module\" src=\"three.js\" defer></script>\n";
    $html_ct .= "</head>\n";
    $html_ct .= "<body>\n";
    $html_ct .= "</body>\n";
    $html_ct .= "</html>";


    $script_content = <<<EOD
    import * as THREE from 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.module.js';
    import { imageData } from './dataImages.js';
    
    let scene, camera, renderer;
    let ambientLight, directionalLight;
    let cube, cubeSize;
    let images = [];
    
    function init() {
        // SCENE
        scene = new THREE.Scene();
    
        // RENDERER
        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);
    
        // CAMERA
        camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 1, 10000);
        camera.position.set(-1200, 900, -1200);
        camera.lookAt(new THREE.Vector3(0, 0, 0));
        scene.add(camera);
    
        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }
    
        window.addEventListener('resize', onWindowResize);
    
        // LIGHTS
        ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
        scene.add(ambientLight);
    
        directionalLight = new THREE.DirectionalLight(0xffffff, 0.6);
        scene.add(directionalLight);
    
        // MAIN CUBE 3D
        let loader = new THREE.TextureLoader();
        let textureEpitech = loader.load('../generator/epitech.png');
    
        const geometry = new THREE.BoxGeometry(150, 150, 150);
        const material = new THREE.MeshPhongMaterial({ map: textureEpitech });
    
        cube = new THREE.Mesh(geometry, material);
        scene.add(cube);
    
        // SIZE MAIN CUBE 3D
        const box = new THREE.Box3().setFromObject(cube);
        cubeSize = box.getSize(new THREE.Vector3()).length();
    
        // IMAGES IN CUBE 3D
        imageData.forEach((image) => {
            let cubeTexture = loader.load(image.path);
    
            const cubeShape = new THREE.BoxGeometry(100, 100, 100);
            const cubeBg = new THREE.MeshPhongMaterial({ map: cubeTexture });
            const cubeImage = new THREE.Mesh(cubeShape, cubeBg);
    
            images.push(cubeImage);
            scene.add(cubeImage);
        });
    
        // LOOP
        render();
    }
    
    function render() {
        cube.rotation.x += 0.005;
        cube.rotation.y += 0.01;
    
        const radius = cubeSize * 2;
        const speed = 0.0009;
        const time = Date.now() * speed;
    
        images.forEach((image, index) => {
            const angle = (index / images.length) * Math.PI * 2;
    
            const x = Math.sin(time + angle) * radius;
            const z = Math.cos(time + angle) * radius;
            const y = Math.sin((time + angle) * 0.5) * radius;
    
            image.position.set(x, y, z);
        });
    
        renderer.render(scene, camera);
        requestAnimationFrame(render);
    }
    
    init();
    EOD;

    if (!is_dir("./animation/")) {
        mkdir("./animation/");
    }
    file_put_contents("./animation/three.html", $html_ct);
    file_put_contents("./animation/dataImages.js", $data);
    file_put_contents("./animation/three.js", $script_content);
}
?>