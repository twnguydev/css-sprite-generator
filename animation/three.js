import * as THREE from 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.module.js';
import { imageData } from './dataImages.js';

let scene, camera, renderer;
let ambientLight, directionalLight;
let cube, cubeSize;
let images = [];

function init() {
    scene = new THREE.Scene();

    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);

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

    ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
    scene.add(ambientLight);

    directionalLight = new THREE.DirectionalLight(0xffffff, 0.6);
    scene.add(directionalLight);

    let loader = new THREE.TextureLoader();
    let textureEpitech = loader.load('../generator/epitech.png');

    const geometry = new THREE.BoxGeometry(150, 150, 150);
    const material = new THREE.MeshPhongMaterial({ map: textureEpitech });

    cube = new THREE.Mesh(geometry, material);
    scene.add(cube);

    const box = new THREE.Box3().setFromObject(cube);
    cubeSize = box.getSize(new THREE.Vector3()).length();

    imageData.forEach((image) => {
        let cubeTexture = loader.load(image.path);

        const cubeShape = new THREE.BoxGeometry(100, 100, 100);
        const cubeBg = new THREE.MeshPhongMaterial({ map: cubeTexture });
        const cubeImage = new THREE.Mesh(cubeShape, cubeBg);

        images.push(cubeImage);
        scene.add(cubeImage);
    });

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