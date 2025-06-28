import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Heart, Users, Clock, Award, ArrowRight, PawPrintIcon as Paw, Mail, Phone, MapPin } from "lucide-react"
import Link from "next/link"
import Image from "next/image"

export default function VolunteeringPage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-orange-50 via-white to-red-50">
      {/* Navigation */}
      <nav className="bg-white/95 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <Link href="/" className="flex items-center space-x-3">
              <div className="w-10 h-10 bg-gradient-to-br from-orange-600 to-red-600 rounded-full flex items-center justify-center">
                <Paw className="w-6 h-6 text-white" />
              </div>
              <span className="text-xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                Help Animals Kosovo
              </span>
            </Link>
            <div className="hidden md:flex items-center space-x-8">
              <Link href="/#about" className="text-gray-700 hover:text-orange-600 transition-colors">
                About
              </Link>
              <Link href="#volunteer" className="text-gray-700 hover:text-orange-600 transition-colors">
                Volunteer
              </Link>
              <Link href="#contact" className="text-gray-700 hover:text-orange-600 transition-colors">
                Contact
              </Link>
              <Link href="/">
                <Button variant="outline" className="border-orange-600 text-orange-600 hover:bg-orange-50">
                  Back to Home
                </Button>
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="relative py-20 lg:py-32 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-orange-600/20 to-red-600/20"></div>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
          <div className="text-center space-y-8">
            <Badge className="bg-orange-100 text-orange-800 hover:bg-orange-200">🤝 Make a Difference</Badge>
            <h1 className="text-4xl lg:text-6xl font-bold leading-tight">
              <span className="bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                Be the Voice
              </span>
              <br />
              of the Voiceless
            </h1>
            <p className="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
              Join us in making a difference for animals in need across Kosovo. Every volunteer hour creates ripples of
              positive change in our community.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button
                size="lg"
                className="bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700"
              >
                Volunteer Now <ArrowRight className="ml-2 w-5 h-5" />
              </Button>
              <Button size="lg" variant="outline" className="border-orange-600 text-orange-600 hover:bg-orange-50">
                Learn More
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div className="space-y-6">
              <Badge className="bg-orange-100 text-orange-800 hover:bg-orange-200">🎯 Our Mission</Badge>
              <h2 className="text-3xl lg:text-4xl font-bold">
                About <span className="text-orange-600">Our Organization</span>
              </h2>
              <p className="text-lg text-gray-600 leading-relaxed">
                We are a non-profit organization dedicated to rescuing, rehabilitating, and rehoming animals throughout
                Kosovo. Our goal is to create a compassionate society where every animal receives the care, love, and
                respect they deserve.
              </p>
              <p className="text-lg text-gray-600 leading-relaxed">
                Through community outreach, education, and hands-on care, we work tirelessly to improve the lives of
                animals in need while building a network of passionate volunteers who share our vision.
              </p>
              <div className="grid grid-cols-2 gap-6">
                <div className="text-center">
                  <div className="text-3xl font-bold text-orange-600">500+</div>
                  <div className="text-gray-600">Animals Rescued</div>
                </div>
                <div className="text-center">
                  <div className="text-3xl font-bold text-red-600">200+</div>
                  <div className="text-gray-600">Active Volunteers</div>
                </div>
              </div>
            </div>
            <div className="relative">
              <div className="absolute inset-0 bg-gradient-to-br from-orange-400 to-red-400 rounded-3xl transform rotate-6"></div>
              <Image
                src="/placeholder.svg?height=500&width=600"
                alt="Volunteers helping animals"
                width={600}
                height={500}
                className="relative rounded-3xl shadow-2xl object-cover"
              />
            </div>
          </div>
        </div>
      </section>

      {/* Volunteer Opportunities */}
      <section id="volunteer" className="py-20 bg-gradient-to-br from-gray-50 to-orange-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <Badge className="bg-red-100 text-red-800 hover:bg-red-200 mb-4">🌟 Get Involved</Badge>
            <h2 className="text-3xl lg:text-4xl font-bold mb-4">
              Volunteer <span className="text-red-600">Opportunities</span>
            </h2>
            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
              Make a real impact by volunteering your time. From fostering and feeding to adoption events and transport,
              your help changes lives.
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-6 text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                  <Heart className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Animal Care</h3>
                <p className="text-gray-600 leading-relaxed">
                  Help with daily feeding, cleaning, and providing love to animals in our shelter.
                </p>
              </CardContent>
            </Card>

            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-6 text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                  <Users className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Adoption Events</h3>
                <p className="text-gray-600 leading-relaxed">
                  Assist at adoption events and help connect animals with their forever families.
                </p>
              </CardContent>
            </Card>

            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-6 text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                  <Clock className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Fostering</h3>
                <p className="text-gray-600 leading-relaxed">
                  Provide temporary homes for animals who need extra care and attention.
                </p>
              </CardContent>
            </Card>

            <Card className="group hover:shadow-xl transition-all duration-300 border-0 shadow-lg">
              <CardContent className="p-6 text-center">
                <div className="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                  <Award className="w-8 h-8 text-white" />
                </div>
                <h3 className="text-xl font-bold mb-4">Education</h3>
                <p className="text-gray-600 leading-relaxed">
                  Help educate the community about responsible pet ownership and animal welfare.
                </p>
              </CardContent>
            </Card>
          </div>

          <div className="text-center mt-12">
            <Button
              size="lg"
              className="bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700"
            >
              Get Involved Today <ArrowRight className="ml-2 w-5 h-5" />
            </Button>
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section id="contact" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-12">
            <div className="space-y-8">
              <div>
                <Badge className="bg-orange-100 text-orange-800 hover:bg-orange-200 mb-4">📞 Get in Touch</Badge>
                <h2 className="text-3xl lg:text-4xl font-bold mb-4">Contact Us</h2>
                <p className="text-lg text-gray-600 leading-relaxed">
                  Ready to make a difference? We'd love to hear from you! Whether you have questions about volunteering,
                  want to learn more about our mission, or are ready to get started, we're here to help.
                </p>
              </div>

              <div className="space-y-6">
                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <Mail className="w-6 h-6 text-orange-600" />
                  </div>
                  <div>
                    <div className="font-semibold">Email Us</div>
                    <div className="text-gray-600">info@helpanimalskosovo.org</div>
                  </div>
                </div>

                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <Phone className="w-6 h-6 text-red-600" />
                  </div>
                  <div>
                    <div className="font-semibold">Call Us</div>
                    <div className="text-gray-600">+383 44 123 456</div>
                  </div>
                </div>

                <div className="flex items-center space-x-4">
                  <div className="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <MapPin className="w-6 h-6 text-purple-600" />
                  </div>
                  <div>
                    <div className="font-semibold">Visit Us</div>
                    <div className="text-gray-600">Pristina, Kosovo</div>
                  </div>
                </div>
              </div>
            </div>

            <Card className="shadow-xl border-0">
              <CardContent className="p-8">
                <form className="space-y-6">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                    <Input placeholder="Enter your full name" className="border-gray-300" />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <Input type="email" placeholder="Enter your email" className="border-gray-300" />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <Textarea
                      placeholder="Tell us how you'd like to help or any questions you have..."
                      rows={5}
                      className="border-gray-300"
                    />
                  </div>
                  <Button className="w-full bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700">
                    Send Message
                  </Button>
                </form>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <p className="text-gray-400">&copy; 2025 Help Animals Kosovo. All rights reserved.</p>
        </div>
      </footer>
    </div>
  )
}
