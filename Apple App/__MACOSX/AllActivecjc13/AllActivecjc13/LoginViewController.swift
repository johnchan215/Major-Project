//
//  LoginViewController.swift
//  AllActivecjc13
//
//  Created by administrator on 19/04/2017.
//  Copyright © 2017 cjc13. All rights reserved.
//

import UIKit

class LoginViewController: UIViewController {

    @IBOutlet weak var usernameTextField: UITextField!
    @IBOutlet weak var passwordTextField: UITextField!
    @IBOutlet weak var userLoginButton: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
        
        // Look for single or multiple taps
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(HealthViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)

    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    // Function to dismiss keyboard when clicked out
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    // Function for the alert popup messages
    func alertMessage(userMessage: String) {
        let alert = UIAlertController(title: "Alert", message: userMessage, preferredStyle: UIAlertControllerStyle.alert)
        
        let confirm = UIAlertAction(title: "Confirm", style: UIAlertActionStyle.default, handler: nil)
        
        alert.addAction(confirm)
        
        self.present(alert, animated: true, completion: nil)
    }
    
    // LOGIN BUTTON FUNCTION
    @IBAction func loginButton(_ sender: Any) {
        
        let username = usernameTextField.text
        let password = passwordTextField.text
        
        // Validate if username or password fields are empty
        if((username?.isEmpty)! || (password?.isEmpty)!) {
            // Display alert message
            alertMessage(userMessage: "All fields are required")
            return
        }
        
        // Send user data to script
        let url = NSURL(string:"http://users.aber.ac.uk/cjc13/major_project/AppLogin.php")
        let request = NSMutableURLRequest(url: url! as URL)
        request.httpMethod = "POST"
        
        let postString = "username="+username!+"&password="+password!
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        // Creating task to send post request
        let task = URLSession.shared.dataTask(with: request as URLRequest) {
            data, response, error in
            
            if error != nil {
                print("error=\(String(describing: error))")
                return
            }
            
            // Parsing response
            do {
                // Converting Response to NSDictionary
                let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                
                // Parsing JSON
                if let parseJSON = json {
                    var msg : String!
                    msg = parseJSON["status"] as! String?
                    print("result: \(msg)")
                    
                    // Send to main thread (specifically for alerts)
                    DispatchQueue.main.async {
                        // Login success
                        if(msg == "Success") {
                            UserDefaults.standard.set(true, forKey: "isUserLoggedIn")
                            UserDefaults.standard.synchronize();
                        
                            self.dismiss(animated: true, completion: nil)
                        
                        // Login failed
                        } else {
                            self.alertMessage(userMessage: "Username or password do not match")
                        }
                    }
                }
                
            } catch {
                print(error)
                
            }
            
        }
        task.resume()
        
    }
        
}
