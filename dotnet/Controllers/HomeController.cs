using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Security.Claims;
using Newtonsoft.Json;
using Newtonsoft.Json.Serialization;
using System.IdentityModel.Tokens;
using System.IdentityModel.Tokens.Jwt;
using Jose.jwe;
using DotNet.Models;
using System.Text;
namespace DotNet.Controllers
{
    public class HomeController : Controller
    {
        private string _url = "http://localhost:3000";
        private string _secret = "TOKEN";
        //
        // GET: /Home/
        public ActionResult Index()
        {
            var payload = new Dictionary<string, object>
            {
                {"resource", new { question = 1}},
                {"params", new {} }
            };
            var vm = new ViewModel() { url = _url, token = Jose.JWT.Encode(payload, Encoding.ASCII.GetBytes(_secret), Jose.JwsAlgorithm.HS256) };
            return View(vm);
        }
	}
}