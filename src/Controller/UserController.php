<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
   * @Route("/add", name="add", methods={"POST"})
   */

    public function register_new_user(Request $request, UserPasswordHasherInterface $passwordHash,UserRepository $userRepository): Response{
        $login = $request->get('login');
        $password = $request->get("password");
        $data = json_decode($request->getContent(), true);

        if ($data["login"]=="") {
            return $this->json ([
                'status'=>400,
                'message'=>"Enter login"
            ]);
        }

        if ($data["password"] == ""){
            return $this->json ([
                'status'=>400,
                'message'=>"Enter password"
            ]);
        }

        if (count($userRepository->findBy(['login'=>$data["login"]])) > 0){
            return $this->json ([
                'status'=>400,
                'message'=>"This login is already taken, choose another"
            ]);
        }

        $user = new User();
        $user->setLogin($data["login"]);
        $user->setPassword($data["password"]);

        $add = $this->getDoctrine()->getManager();
        $add->persist($user);
        $add->flush();

        return $this->json([
            'status'=>200,
            'message'=>"OK"
        ]);
    }
}
