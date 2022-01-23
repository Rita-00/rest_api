<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;


/**
 * @Route("/file")
 */
class FileController extends AbstractController
{
    //**
    //   * @Route("/file", name="file")
    //  */
    /* public function index(): Response
     {
         return $this->render('file/index.html.twig', [
             'controller_name' => 'FileController',
         ]);
     }*/

    /**
     * @Route("/file", name="add", methods={"POST"})
     */
    public function add_file(Request $request): Response
    {
        $data = $request->files->get('file');

        if ($data) {

            $file = new File();
            $file->setName($data->getClientOriginalName());

            $add = $this->getDoctrine()->getManager();
            $add->persist($file);
            $add->flush();

            return $this->json([
                'status' => "200",
                'message' => "OK",
            ]);
        }
        return $this->json([
            'status' => "400",
            'message' => "Incorrect data",
        ]);
    }

    /**
     * @Route("/get", name="get", methods={"GET"})
     */
    public function get_all_file(Request $request,FileRepository $fileRepository): Response
    {
        $data = $fileRepository->findAll();
        $result=[];

        if (count($data) > 0) {
            foreach ($data as $file) {
                $result[] = [
                    'fileName' => $file->getOriginalName()
                ];
            }
            return $this->json ([
                'status'=>200,
                'files'=>$result
            ]);
        }
        return $this->response([
            'status' => "201",
            'message' => "No Files",
        ]);
    }
    /**
     * @Route("/get/{id}", name="get", methods={"GET"})
     */
    public function get_file(Request $request,FileRepository $fileRepository,$id): Response
    {
        $data = $fileRepository->find($id);

        if ($data) {
            return $this->json ([
                'status'=>200,
                'files'=>$data
            ]);
        }
        return $this->json([
            'status' => "400",
            'message' => "No Files",
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete_file(Request $request, FileRepository $fileRepository, $id) : Response
    {
        $file = $fileRepository->find($id);

        if($file) {
            $del = $this->getDoctrine()->getManager();
            $del->remove($file);
            $del->flush();

            $filesystem = new Filesystem();
            $filesystem->remove([$file]);

            return $this->json([
                'status' => "200",
                'message' => "OK",
            ]);

        } else {
            return $this->json ([
                'status'=>400,
                'message'=>"No such file"
            ]);
        }
    }
}
